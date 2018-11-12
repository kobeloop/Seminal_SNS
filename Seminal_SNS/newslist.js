var json_data = {
    rss_url: "https://news.yahoo.co.jp/pickup/science/rss.xml"
};


// RSS形式のニュース一覧を取得
$.ajax({
    url     : "azureのロジックアップ",
    type    : "POST",
    async   : true,
    dataType: "json",
    contentType: 'application/json',
    data    : JSON.stringify(json_data),
    success : function(elements)
     {
        // 変数の宣言
        var display_num = 5; // 指定件数
        var ul, li, a;

        // <ul>要素の生成
        ul = $('<ul>');
        ul.addClass('mw-list');

        // RSS一覧を取得件数文だけループ
        for (var i=0;elements.length;i++){

            // 指定件数に達したらループ終了
            if (i ==display_num){
                break;
            }
            // <a>要素の生成
            a =$('<a>');
            a.text(elements[i].title);
            a.addClass('mw-list-item-link');
            a.attr({
                href: elements[i].primaryLink,
                target: "_blank",
            })

            // <li>要素の生成・<a>要素の追加
            li = $('<li>');
            li.append(a);

            // <li>要素を<ul>要素に追加
            ul.append(li);   
        };
        // HTMLにRSS一覧を追加
        $('#my-widget').append(ul);
    }
});