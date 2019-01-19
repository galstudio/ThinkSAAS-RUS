var E = window.wangEditor
var editor = new E('#tseditor')
// var editor = new E( document.getElementById('editor') )

// пользовательская конфигурация
editor.customConfig.menus = [
    'head',  // заголовок
    'bold',  // жирный
    'italic',  // курсив
    'underline',  // подчеркивание
    'strikeThrough',  // зачеркивание
    'foreColor',  // цвет текста
    'backColor',  // цвет фона
    'link',  // ссылка
    'list',  // список
    'justify',  // центровка
    'quote',  // цитата
    'image',  // изображение
    'code',  // код
    'undo',  // отмена
    'redo'  // повтор
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
editor.create()
// инициализация textarea
content.val(editor.txt.html())
