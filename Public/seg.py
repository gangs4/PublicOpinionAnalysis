#!/usr/bin/python
#coding=utf-8
import sys, os
from pyltp import Segmentor, Postagger, Parser, NamedEntityRecognizer, SementicRoleLabeller

MODELDIR="/home/liuqi/ltp/pyltp/ltp_data/"

def seg(content):
        # Set your own model path
    MODELDIR="/home/liuqi/ltp/pyltp/ltp_data/"
    segmentor = Segmentor()
    segmentor.load(MODELDIR+"cws.model")
    tWords = segmentor.segment(content)
    return tWords

# se = "睡前旧了一把，《SMAP×SMAP.111128 超豪华动漫名曲歌谣祭》  http://t.cn/zWpWSF5  TOUCH真是听它千遍也不厌倦[可怜] 明天再继续第二弹..."

# stopFile = "/home/liuqi/ltp/text/stop.txt"
# dic = []
# fin = open(stopFile,"r")
# sentence = fin.readline()
# while sentence:
#     dic.append(sentence[:-1])
#     sentence = fin.readline()
# fin.close()

# name = sys.argv[1]
# fin = open(name)
# total = ""
# content = fin.readline()
# while content:
#     total = total + content
#     content = fin.readline()
# fin.close()

# l = len(sys.argv)
# total = ""
# for i in xrange(l-1):
#     total =  total + sys.argv[i+1];
# print "total : ",total

# print "================="
# print se
# print "================="

name = sys.argv[1]
fin = open(name)
total = ""
line = fin.readline()
while line:
    total  = total + line
    line = fin.readline()
fin.close()

ans =  seg(total)
string = ""
for word in ans:
    # if word in dic:
    #     continue
    string = string + word + " "
string = string[:-1]
print string
# print "hello world"