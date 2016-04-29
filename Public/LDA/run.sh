#!/bin/bash

helpInfo(){
	echo "
	./run.sh /directory/ fileName.txt
	（例如 ./run.sh /Users/liu/Desktop/LDA/ sx.txt）
	
	参数解释：
	第一个参数 /directory/ 是一个可写目录绝对路径(最后加/)，保存着第二个参数 文件名 和 5个模型数据文件结构如下
		
		.
		├── .others.gz
		├── .tassign.gz
		├── .theta.gz
		├── .twords.gz
		├── .wordmap.gz
		└── fileName.txt

		0 directories, 6 files

	文件保存着用户上传的数据，用utf-8编码。

	输出：
	输出的文件很多，所有文件都是以fileName为前缀的，读取完毕之后按照这个规则删除即可，不要删除模型文件
	在filename..theta文件中含有类似如下内容空格分割
	
	0:0.012506012506012507 1:0.01575276575276575 2:0.07744107744107745
	3:0.13047138047138046 4:0.04280904280904281 5:0.021164021164021166
	6:0.4280904280904281 7:0.2257094757094757 8:0.04605579605579606 
	
	分类对应关系为：
	army sport tech entrepreneurship cartoon constellation game education travelPhotoFood

	"
}

# 参数检查
if [[ $1 == '' || $2 == '' ]]; then
	helpInfo;
	exit -1;
fi

# savePath="/Users/liu/project/LDA_TEST/data/newOne/"
# originFile="sx.txt"
savePath=$1
originFile=$2


# cut words # 这里注意改变一下cut.py的路径 注意使用python3，路径可能要到python文件第一行设置。
python3 ./Public/LDA/cut.py $savePath$originFile

dataFile=$originFile".out.txt"

gzip -kf $savePath$dataFile

# analyze $savePath $dataFile".gz"

# LDA分析 改变jar包路径
java -Xms512m -Xmx512m -jar ./Public/LDA/lda.jar  -inf -niters 50 -twords 20  -dir $savePath -dfile $dataFile".gz"

gunzip -fk "$savePath$dataFile".*.gz
rm "$savePath$dataFile".*.gz


# 以下两句是脚本输出，不用理睬，不要使用脚本输出的信息，直接读文件就好。
#meaningMap=("army" "sport" "tech" "entrepreneurship" "cartoon" "constellation" "game" "education" "travelPhotoFood")
#cat $savePath$dataFile$thetaEnd | perl -lape 's/ /\n/g' | awk 'BEGIN {i=1;str="army sport tech entrepreneurship cartoon constellation game education travelPhotoFood";split(str,map," ")}{split($0, s, ":");printf("%30s %3f%%\n", map[i], s[2] * 100); i++;}'

