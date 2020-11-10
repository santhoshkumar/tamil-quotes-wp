async function fetchquotes() {
    const hellotaquotesClass = document.getElementsByClassName('ta_quotes');
    try {
        const response = await fetch('https://tamilsms.mskian.workers.dev/');
        const data = await response.json();
        console.log(data);
        if (data === false || data === '') {
            if (hellotaquotesClass != null) {
                let quotes_msg = '<p>API URL Empty</p>';
                quotes_text(hellotaquotesClass, quotes_msg);
            }
        } else if (hellotaquotesClass != null) {
            const postdata = data[0].content;
            const tosquotes = postdata.replace(/(?:\r\n|\r|\n)/g, '<br>');
            let quotes_msg = '<p>' + tosquotes + '</p>';
            quotes_text(hellotaquotesClass, quotes_msg);
        }
    } catch (exception) {
        if (hellotaquotesClass != null) {
            let quotes_msg = '<p>Connection Lost or Enter a valid API URL</p>';
            quotes_text(hellotaquotesClass, quotes_msg);
        }
    }
}

function quotes_text(hellotaquotesClass, text) {
    for (let i = 0; i < hellotaquotesClass.length; i++) {
        hellotaquotesClass[i].innerHTML = '<p>தமிழ் கோட்ஸ் 💌</p>';
        setTimeout(() => {
            hellotaquotesClass[i].innerHTML = text;
        }, 1000);
    }
}
fetchquotes();
setInterval(fetchquotes, 60 * 2000);