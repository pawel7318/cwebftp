var pwgen = null;
var result = null;

function generate() {

    pwgen = new PWGen();

    result = pwgen.generate();

    document.getElementById('passwd1').value=result;
    document.getElementById('passwd2').value=result;
    document.getElementById('plain-text').innerHTML=result;
}