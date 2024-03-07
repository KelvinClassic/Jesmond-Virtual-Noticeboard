function countRemainingWords(elem){
    const content = elem.value.trim();
    const count_words = content.split(" ");
    const remaining_words_content = elem.nextElementSibling;

    if(count_words.length > 50){
        elem.value = count_words.slice(0, 50).join(" ");
    }
    const remaining_words = 50 - count_words.length;

    if(remaining_words == 49 && content == ""){
        remaining_words_content.innerHTML = "";
    }
    else if(remaining_words < 0){
        remaining_words_content.innerHTML = `0 remaining word(s)`;
    }
    else{
        remaining_words_content.innerHTML = `${remaining_words} remaining word(s)`;
    }
}