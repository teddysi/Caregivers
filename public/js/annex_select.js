$(document).ready(function(){
    selectTypeChange();
});

function selectTypeChange() {
    var value = document.getElementById("selectType").value;

    if (value == '0') {
        document.getElementById("inputFile").style.display = 'none';
        document.getElementById("inputURL").style.display = 'block';
    } else if (value == '1') {
        document.getElementById("inputFile").style.display = 'none';
        document.getElementById("inputURL").style.display = 'block';
    } else if (value == '2') {
        document.getElementById("inputFile").style.display = 'block';
        document.getElementById("inputURL").style.display = 'none';
    } else {
        document.getElementById("inputFile").style.display = 'none';
        document.getElementById("inputURL").style.display = 'none';
    }
}