var start=0;
var tim='###';
function ntoms(s){
    m = parseInt(s/60);
    s %=60;
    if( s<10) s = '0'+s;
    return '['+m+':'+s+']';
};
function progress() {
    // progress.php を呼び出して進行状況を取得する
    jQuery.getJSON('js/progress.php', { 'd': new Date().getTime() }, function(json) {
        if( json.current ){
            tim  = ntoms(parseInt(((json.total-json.current)*((new Date()).getTime()-start)/json.current+500)/1000));
        }else{
            start = (new Date()).getTime();
        }
        // プログレスバーを更新
        jQuery("#progressbar").reportprogress(parseInt(json.current / json.total * 100));
        
        // アップロード済みサイズを表示
        jQuery("#status").html((json.current>>20) + ' MB / ' + (json.total>>20) + ' MB' + tim );
        // アップロード終了時
        if (json.done == 1) {
                // アップロードアップロード速度を表示
                jQuery("#status").html((json.rate>>20) + ' Mbps');
        }else{
                setTimeout('progress()',1000);
        }
    });
};

function progressStart(){
    // プログレスバーを初期化
    jQuery("#progressbar").reportprogress(0);
    // タイマー開始
    setTimeout('progress()', 500);
    start = (new Date()).getTime();
    // 初回起動
    progress();
    return false;
};
