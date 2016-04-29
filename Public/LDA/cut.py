#!/usr/local/lib/python3.4
__author__ = 'liu'
import jieba
import jieba.analyse
import sys

def cut():
     print("finish cuting file : " + sys.argv[1] + ' -> ' + sys.argv[1] + '.out.txt')
     with open(sys.argv[1], "r") as fin:
         print("read1")
         with open(sys.argv[1] + '.out.txt', "w") as fout:
             print("read successfully")
             centence = fin.readline()
             print("read3")
             print("read4")
             fout.write(centence)
#             while centence:
                 # remove centence too short
 #                if (centence.__len__() > 15):
  #                   saveString = ' '.join(jieba.analyse.extract_tags(centence, 50)) + ' '
   #                  fout.write(saveString)
    #                 print(saveString)
             print("read2")
             centence = fin.readline()
     print("finish cuting file : " + sys.argv[1] + ' -> ' + sys.argv[1] + '.out.txt')


if __name__ == '__main__':
    if (sys.argv.__len__()<=1):
        print("must input a fileName!")
    else:
        cut()
