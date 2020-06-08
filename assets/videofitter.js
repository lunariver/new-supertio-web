jQuery(function($){
	/******************************************
	init
	******************************************/
	var winH = $(window).height();
	$('.box').outerHeight(winH);
	
	$(window).on('load',function(){
		setBgImg($('.fitMovie'));
	});
				 
	$(window).on('resize',function(){
		winH = $(window).height();
		$('.box').outerHeight(winH);
		
		setBgImg($('.fitMovie'));
	});

	function setBgImg(object){
		//画像サイズ取得
		var imgW = object.width();
		var imgH = object.height();
		
		//ウィンドウサイズ取得
		var winW = $(window).width();
		var winH = $(window).height();	

		//幅・高さの拡大・縮小率取得
		var scaleW = winW / imgW;
		var scaleH = winH / imgH;

		//幅・高さの拡大・縮小率の大きいものを取得
		var fixScale = Math.max(scaleW, scaleH);

		//画像の幅高さを設定
		var setW = imgW * fixScale;
		var setH = imgH * fixScale;
		
		//画像の位置を設定
		var moveX = Math.floor((winW - setW) / 2);
		var moveY = Math.floor((winH - setH) / 2);
	
		//設定した数値でスタイルを適用
		object.css({
			'width': setW,
			'height': setH,
			'left' : moveX,
			'top' : moveY
		});		
	}
	
});	