<?php
namespace app\BJPrExO9\controller;

use think\facade\View;
use think\facade\Session;
use think\facade\Request;

use app\BJPrExO9\controller\Admin;

use app\admin\model\CmsTag;
use app\admin\model\CmsCategory;
use app\admin\model\LogAdminOperation;
use app\admin\model\CmsArticle;
use app\admin\model\CmsArticleData;


class Cms extends Admin{
    private $cms_hint_array = array(
        'tag_ids'=> '请选择标签',
        'author'=> '请填写作者',
        'intro'=> '请填写内容',
        'keyword'=> '请填写关键字',
        'content_type'=> '请选择内容类型',
    );

    public function __construct(){
        parent::__construct();
        View::assign('cms_tag_image_onoff', $this->cms_tag_image_onoff);
        View::assign('cms_category_image_onoff', $this->cms_category_image_onoff);
        View::assign('cms_article', $this->cms_article);
    }

    /**
     * 文章分类管理-列表
     *
     * @return void
     */
    public function category(){
        $list = CmsCategory::order('sort asc, category_id desc')->select();
        View::assign('list', $list);
        return View::fetch();
    }

    /**
     * 文章管理-列表
     *
     * @return void
     */
    public function article(){
        $title = Request::instance()->param('title', '');
        $category_id = Request::instance()->param('category_id', '');
        $tag_id = Request::instance()->param('tag_id', '');
        $author = Request::instance()->param('author', '');
        $trait = Request::instance()->param('trait', '');

        $field = 'article_id, category_id, tag_ids, title, author, intro, keyword, image, sort, content_type';
        $article = new CmsArticle;
        $article = $article->field($field);
        if($trait != ''){
            $trait_ids = CmsArticleData::where('is_' . $trait, 1)->column('article_id');
        }
        $article = ($trait != '') ? $article->where('article_id', 'in', $trait_ids) : $article;
        $article = ($title != '') ? $article->where('title', 'like', '%' . $title . '%') : $article;
        $article = ($category_id != '') ? $article->where('category_id', $category_id) : $article;
        $article = ($tag_id != '') ? $article->where('tag_ids', 'like', '%,' . $tag_id . ',%') : $article;
        $article = ($author != '') ? $article->where('author', 'like', '%' . $author . '%') : $article;
        $list = $article->order('article_id desc')->paginate($this->page_number, false,['query'=>request()->param()]);
        foreach($list as &$l){
            $l['tag_ids'] = CmsTag::where('tag_id', 'in', $l['tag_ids'])->column('tag_name');
            $l['tag_ids'] = implode(',', $l['tag_ids']);
        }
        //删除未被上传的图片
        $article_images = Session::get('article_content_images');
        if($article_images){
            foreach($article_images as $k => $v){
                delete_image($v);
                unset($article_images[$k]);
            }
        }
        Session::set('article_content_images', array());
        $this->many_assign(['list'=> $list, 'title'=> $title, 'category_id'=> $category_id, 'tag_id'=> $tag_id, 'author'=> $author, 'trait'=> $trait]);
        //分类和标签
        $category = CmsCategory::field('category_id, category_name, sort')->order('sort asc')->select();
        $tag = CmsTag::field('tag_id, tag_name, sort')->order('sort asc')->select();
        View::assign('category', $category);
        View::assign('tag', $tag);
        return View::fetch();
    }
}