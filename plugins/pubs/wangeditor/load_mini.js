var E = window.wangEditor
var editor = new E('#tseditor','#tseditor2')
// var editor = new E( document.getElementById('editor') )

// пользовательская конфигурация
editor.customConfig.menus = [
    'bold',  // жирный
    'foreColor',  // цвет текста
    'link',  // ссылка
    'emoticon',  // смайлики
    'image',  // изображение
]
editor.customConfig.zIndex = 100
// настройка адресов на сервере
editor.customConfig.uploadImgServer = siteUrl+'index.php?app=pubs&ac=wangeditor&ts=photo'
// ограничение размера изображения до 2Мб
editor.customConfig.uploadImgMaxSize = 2 * 1024 * 1024
// ограничение загрузки до 5 изображений одновременно
editor.customConfig.uploadImgMaxLength = 5
editor.customConfig.uploadFileName = 'photo'
var content = $('textarea[name="content"]')
editor.customConfig.onchange = function (html) {
    // синхронизация textarea
    content.val(filterXSS(html))
}
//решение проблем с iphone
editor.customConfig.onblur = function (html) {
    // html содержание в редакторе
    console.log('onblur', html)
    content.val(filterXSS(html))
}
editor.create()
