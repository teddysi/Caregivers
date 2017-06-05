$(document).ready(function(){
    selectTypeChange();
});

function selectTypeChange() {
    var value = document.getElementById("selectType").value;

    if (value == 'radio') {
        document.getElementById("inputOptions").style.display = 'block';
    } else {
        document.getElementById("inputOptions").style.display = 'none';
    }
}