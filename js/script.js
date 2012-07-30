var infinite_scroll = {
	loading: {
		img: wpis_vars.img,
		msgText: wpis_vars.msgText,
		finishedMsg: wpis_vars.finishedMsg
	},
	"nextSelector": wpis_vars.nextSelector,
	"navSelector": wpis_vars.navSelector,
	"itemSelector": wpis_vars.itemSelector,
	"contentSelector": wpis_vars.contentSelector
};
jQuery( infinite_scroll.contentSelector ).infinitescroll( infinite_scroll );