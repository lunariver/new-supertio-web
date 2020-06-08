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
		//�摜�T�C�Y�擾
		var imgW = object.width();
		var imgH = object.height();
		
		//�E�B���h�E�T�C�Y�擾
		var winW = $(window).width();
		var winH = $(window).height();	

		//���E�����̊g��E�k�����擾
		var scaleW = winW / imgW;
		var scaleH = winH / imgH;

		//���E�����̊g��E�k�����̑傫�����̂��擾
		var fixScale = Math.max(scaleW, scaleH);

		//�摜�̕�������ݒ�
		var setW = imgW * fixScale;
		var setH = imgH * fixScale;
		
		//�摜�̈ʒu��ݒ�
		var moveX = Math.floor((winW - setW) / 2);
		var moveY = Math.floor((winH - setH) / 2);
	
		//�ݒ肵�����l�ŃX�^�C����K�p
		object.css({
			'width': setW,
			'height': setH,
			'left' : moveX,
			'top' : moveY
		});		
	}
	
});	