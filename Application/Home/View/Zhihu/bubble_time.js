	var diameter = 470,
		format = d3.format(",d");
	var color = d3.scale.category20c();
	var pack = d3.layout.pack()
		.size([diameter+100, diameter ])
		.sort(null)
		.padding(1.5)
		.value(function(d) { return d.size; });
	var svg = d3.select(".round").append("svg")
		.attr("width", diameter+100-4)
		.attr("height", diameter-4)
		.append("g")
		.attr("transform", "translate(2,2)");
	var random = Math.random();
	//d3.json("flare.json?ver="+random,function(error,root){
	try {
			var node = svg.selectAll(".node")
		  .data(pack.nodes(root.children[0]).filter(function(d) { return !d.children; }))
		  .enter().append("g")
		  .attr("class", function(d) { return "node" ; })
		  .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
		node.append("title")
			  .text(function(d) { return d.name; });
		node.append("circle")
				  .attr("r", function(d) { return d.r; })
				  .style("fill",function(d){return color(d.size);});
		node.filter(function(d) { return !d.children; }).append("text")
				  .attr("dy", ".3em")
				  .style("text-anchor", "middle")
				  .style("font-size",function(d){return d.size/4+"px"})
				  .style("font-family","微软雅黑")
				  .style("font-weight","bold")
				  .style("fill","black")
				  .text(function(d) { return d.name.substring(0, d.r / 2); });
	} catch(err){}
	//});
function reDraw(i){
	var random = Math.random();
		//d3.json("flare.json?ver="+random,function(error,root){
	try {	
		var node = svg.selectAll(".node")
			  .data(pack.nodes(root.children[i]).filter(function(d) { return !d.children; }));
		node.enter().append("g")
		.attr("class", "node" );
		var exNode = node.exit();
		exNode.select("circle")
			  .transition()
			  .duration(3000)
			  .attr("r", 0);	
		exNode.select("text")
		 	  .transition()
			  .duration(2000)
			  .style("font-size","0px"); 
		exNode.transition().duration(3000).remove();
		node.append("title");
		node.append("circle");
		node.filter(function(d) { return !d.children; }).append("text");
		node.transition()
		.duration(3000)
		.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
		node.select("title")
			  .transition()
			  .duration(3000)
			  .text(function(d) { return d.name; });
		node.select("circle")
			  .transition()
			  .duration(3000)
			  .attr("r", function(d) { return d.r; })
			  .style("fill",function(d){return color(d.size);});
		node.select("text")
		 	  .transition()
			  .duration(2500)
			  .delay(500)
			  .attr("dy", ".3em")
			  .style("text-anchor", "middle")
			  .style("font-size",function(d){return d.size/4+"px"}) // zws modify
			  .style("font-family","微软雅黑")
			  .style("font-weight","bold")
			  .style("fill","black")
		node.select("text").text(function(d){return d.name;});
	} catch(err){}	
	//});
}
function getFen(time,time2){
	var long1=parseInt(time.substr(0,2)*60)+parseInt(time.substr(3,2));
	var long2=parseInt(time2.substr(0,2)*60)+parseInt(time2.substr(3,2));
	return long1-long2;
};
function setLeft(datas){
	var chushi=10;
	for(var i=0;i<datas.length;i++){
			if(i==0){
					datas[0].left=chushi;
				}
			else{
					var time=datas[i].time;
					var time2=datas[i-1].time;
					datas[i].left=getFen(time,time2)*3.5+datas[i-1].left;
				}
				
		}
	return datas;
};
var animateNum=1;
var c=0;
function showPoint(datas){
	for(var i=0;i<datas.length;i++){
			if(c==i){
				if(i==0){
						$(".timePoint").show();
						//$(".textPoint").show().text(datas[0].title);
				}
				else{
						var time=datas[i].time;
						var time2=datas[i-1].time;
						$(".timePoint").animate({left:(datas[i].left+"px")},getFen(time,time2)*200);	
						$(".textPoint").animate({left:((datas[i].left-35)+"px")},getFen(time,time2)*200,function(){
								var text=datas[animateNum].title;
								animateNum++;
								$(this).text(text);
								reDraw(animateNum-1);
							});
				}
				c++;
			}
		}
		var time=datas[datas.length-1].end;
		var time2=datas[datas.length-1].time;
		var left=getFen(time,time2)*7+datas[datas.length-1].left;		
		$(".timePoint").animate({left:(left+"px")},getFen(time,time2)*300);	
		$(".textPoint").animate({left:((left-35)+"px")},getFen(time,time2)*300,function(){
			$(".timePoint").animate({left:"10px"},300);	
			$(".textPoint").animate({left:"-25px"},300)
				animateNum=1;
				c=0;
				setPoint(dat);
				reDraw(animateNum-1);
			});
}
function setPoint(datas){
	//var d=setLeft(datas);  zhongweisheng modify java evaluate
	var d=datas;
	$(".axisPoint").remove();
	$(".axisTime").remove();
	
	for(var i=0;i<d.length-1;i++){
		var tmp=(((d[i+1].left)-(d[i].left))/2+d[i].left-24);
		var div='<div class="axisPoint" onClick="axisPointClick(this)" id="'+i+'" style="left:'+d[i].left+'px;cursor:pointer"></div><span class="axisTime" style="left:'+(d[i].left-10)+'px;cursor:pointer" onClick="axisPointClick(this)" id="'+i+'">'+d[i].time+'</span>';
		$(".axis").append(div);
	}
	var i=d.length-1;
	var div='<div class="axisPoint" onClick="axisPointClick(this)" id="'+i+'" style="left:'+d[i].left+'px;cursor:pointer"></div><span class="axisTime" style="left:'+(d[i].left-10)+'px;cursor:pointer" onClick="axisPointClick(this)" id="'+i+'">'+d[i].time+'</span>';
	$(".axis").append(div);
	var time=d[d.length-1].end;
	var time2=d[d.length-1].time;
	var left=getFen(time,time2)*7+d[d.length-1].left;
	var div='<div class="axisPoint" style="left:'+left+'px;"></div><span class="axisTime" style="left:'+(left-10)+'px;">'+d[d.length-1].end+'</span>';
	$(".axis").append(div);
	showPoint(d);
};
function axisPointClick(obj){  
			var l=$(obj).css("left");
			var l2=(parseFloat(l.replace("px",""))-35)+"px"; 
			var id=parseInt($(obj).attr("id"));
			reDraw(id);
			if(id==dat.length)
				$(".textPoint").text(dat[id-1].title);
			else
				$(".textPoint").text(dat[id].title);
			$(".timePoint").stop(true).animate({left:l},100);	
			$(".textPoint").stop(true).animate({left:l2},100,function(){
				animateNum=parseInt($(obj).attr("id"));
				c=parseInt($(obj).attr("id"));
				setPoint(dat);
			});
};