$(window).on("load",function(){var a=dimple.newSvg("#bubble-chart","100%",500);d3.tsv("robust-assets/demo-data/dimple/example-data.tsv",function(b){function c(){setTimeout(function(){d.draw(0,!0),e.titleShape.remove(),f.titleShape.remove()},100)}b=dimple.filterData(b,"Date","01/12/2012");var d=new dimple.chart(a,b);d.setBounds(0,0,"100%","100%"),d.setMargins(40,10,0,50);var e=d.addMeasureAxis("x","Unit Sales Monthly Change"),f=d.addMeasureAxis("y","Price Monthly Change");d.addMeasureAxis("z","Operating Profit");d.addSeries(["SKU","Channel"],dimple.plot.bubble),d.defaultColors=[new dimple.color("#673AB7"),new dimple.color("#E91E63")],e.fontSize="12",f.fontSize="12",d.draw(),e.titleShape.remove(),f.titleShape.remove(),$(window).on("resize",c),$(".menu-toggle").on("click",c)})});