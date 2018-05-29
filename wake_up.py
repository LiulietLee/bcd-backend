# 为了防止爬虫服务器 vapor 和 heroku 因长时间不用而关机
# 每个小时第 0 分钟对 vapor 进行一次请求
# 第 30 分钟对 heroku 进行一次请求

import datetime
import sys
import random

now = datetime.datetime.now()
minute = now.minute

url = "https://bilibilicd"
if minute == 0:
    url += ".vapor.cloud"
elif minute == 30:
    url += ".herokuapp.com"
else:
    sys.exit()

url += "/av/info/" + str(random.randint(1, 100000))
# print(url)

import urllib.request
contents = urllib.request.urlopen(url).read()

# print(contents)
