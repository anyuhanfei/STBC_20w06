$(function () {
    $('.btn_return').click(function () {
        history.go(-1)
    })
    function upload(e, i) {
        var file = e.files[0]
        if (e.files) {
            var reader = new FileReader()
            reader.readAsDataURL(e.files[0]);
            reader.onload = function (e) {
                var src = this.result
                $('.img'+i+'').attr('src', src)
            }
        }
    }
})












































