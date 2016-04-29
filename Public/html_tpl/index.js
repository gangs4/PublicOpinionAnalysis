$(document).ready(function () {
  var bubbleChart = new d3.svg.BubbleChart({
    supportResponsive: true,
    //container: => use @default
    size: 600,
    //viewBoxSize: => use @default
    innerRadius: 600 / 6,
    //outerRadius: => use @default
    radiusMin: 30,
    //radiusMax: use @default
    //intersectDelta: use @default
    //intersectInc: use @default
    //circleColor: use @default
    data: {
      items: [
        {text: "user0", count: "66"},
        {text: "user1", count: "38"},
        {text: "user2", count: "22"},
        {text: "user3", count: "27"},
        {text: "user4", count: "29"},
        {text: "user5", count: "19"},
        {text: "user6", count: "33"},
        {text: "user7", count: "24"},
        {text: "user8", count: "20"},
        {text: "user9", count: "22"},
        {text: "user10", count: "23"},
      ],
      eval: function (item) {return item.count;},
      classed: function (item) {return item.text.split(" ").join("");}
    },
    plugins: [
      {
        name: "central-click",
        options: {
          text: "(See more detail)",
          style: {
            "font-size": "12px",
            "font-style": "italic",
            "font-family": "Microsoft Yahei",
            //"font-weight": "700",
            "text-anchor": "middle",
            "fill": "white"
          },
          attr: {dy: "50px"},
          centralClick: function() {
            alert("More details");
          }
        }
      },
      {
        name: "lines",
        options: {
          format: [
            {// Line #1
              textField: "text",
              classed: {text: true},
              style: {
                "font-size": "10px",
                "font-family": "Microsoft Yahei",
                "text-anchor": "middle",
                fill: "white"
              },
              attr: {
                dy: "20px",
                x: function (d) {return d.cx;},
                y: function (d) {return d.cy;}
              }
            },
            {// Line #0
              textField: "count",
              classed: {count: true},
              style: {
                "font-size": "14px",
                "font-family": "Microsoft Yahei",
                "text-anchor": "middle",
                fill: "white"
              },
              attr: {
                dy: "0px",
                x: function (d) {return d.cx;},
                y: function (d) {return d.cy;}
              }
            }
          ],
          centralFormat: [
            {// Line #0
              style: {"font-size": "30px"},
              attr: {dy: "0px"}
            },
            {// Line #1
              style: {"font-size": "20px"},
              attr: {dy: "20px"}
            }
          ]
        }
      }]
  });
});