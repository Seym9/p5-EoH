window.addEventListener("load",() => {
    tinymce.init({
        selector: '.editor',
        language: 'fr_FR',
        width: '100%',
        height: 250,
        max_height: 300,
        plugins: "lists, image, link, code, wordcount, autolink, autosave, table, hr, lists",
        toolbar: "undo redo | formatselect | link image | alignleft aligncenter alignright  | bold italic underline forecolor blockquote | bullist numlist",
        autosave_restore_when_empty: false,
        content_style: "* {font-family: 'Montserrat', sans-serif} p {font-size: 1.5em} p, h1, h2, h3, h4, h5, h6 {color: #000}",
        content_css: ['https://fonts.googleapis.com/css?family=Montserrat'],

        menu: {
            edit: {title: 'Edit', items: 'undo redo | selectall | cut copy paste pastetext '},
            insert: {title: 'Insert', items: 'link | hr'},
            format: {title: 'Format', items: 'bold italic underline strikethrough | code | removeformat'},
            tools: {title: 'Tools', items: 'restoredraft'},
            table: {title: 'Table', items: 'inserttable tableprops deletetable | cell row column'}
        },

    });
});
