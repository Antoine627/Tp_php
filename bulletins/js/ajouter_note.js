function openForm() {
    document.getElementById("noteForm").style.display = "block";
}


function calculate() {
    const note1 = parseFloat(document.getElementById("note1").value) || 0;
    const note2 = parseFloat(document.getElementById("note2").value) || 0;
    const note3 = parseFloat(document.getElementById("note3").value) || 0;
    const note4 = parseFloat(document.getElementById("note4").value) || 0;

    const total = note1 + note2 + note3 + note4;
    const average = total / 4;

    document.getElementById("total").value = total.toFixed(2);
    document.getElementById("average").value = average.toFixed(2);
}
