$(function () {
    var state = true;
    $('.btn_keys').click(function () {
        var that = this
        second = 60
        if ( state && $(that).text() == '点击确认') {
            var countDown = setInterval(function () {
                second--
                $(that).text(second)
                if(second <= 0){
                    clearInterval(countDown)
                    $(that).text('点击确认')
                    state = true;
                }else {
                    $('.acquire').text(second)
                    state = false;
                }
            }, 1000)
        }
    })
})
















