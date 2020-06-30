from openpyxl import load_workbook

import requests

file_name = "C:\\Users\\Administrator\\Desktop\\stbc数据原始数据.xlsx"
domain = "stbc.xmnykf.com"
php_session = 'a84babc878ec3ffa1a9399210aecdb4d'


wb = load_workbook(filename=file_name)

sheets = wb.get_sheet_names()
sheet_first = sheets[0]

ws = wb.get_sheet_by_name(sheet_first)

rows = ws.rows
columns = ws.columns

users = {}
i = 0
for row in rows:
    if i == 0:
        i += 1
        continue
    line = [col.value for col in row]
    users[line[2]] = {'user_id': line[2], 'phone': line[3], 'top_id': line[8]}

wb.close()

url = "http://%s/admin/user/add/submit" % (domain)
cookies = {
    'PHPSESSID': php_session
}


def add_user(user):
    print(user)
    try:
        users[user['top_id']]['phone']
    except BaseException:
        user['top_id'] = 652874
    data = {
        'nickname': user['phone'],
        'user_identity': user['phone'],
        'password': '123456a',
        'level_password': '',
        'top_user_identity': users[user['top_id']]['phone'] if user['top_id'] != 0 else '',
    }
    res = requests.post(url, data=data, cookies=cookies)
    return res


for key, value in users.items():
    print(add_user(value))
