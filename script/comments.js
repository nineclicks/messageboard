window.onload = function() {
    comTemp = document.getElementById("comTemp").innerHTML;
    AddChildren();
}

function AddChildren() {
    for (c in comments) {
        let cur = comments[c];
        if (cur.parent != null && c != postid) {
            par = comments[cur.parent];
            if (par.child == null) {
                par.child = new Array();
            }
            par.child.push(c);
        }
        cur.built = false;
    }

    for (c in comments) {
        BuildComment(c);
    }
}

function BuildComment(id) {
    var com = comments[id];

    if (com.built == true) {
        return;
    }

    var thisDiv = document.getElementById(id);
    if (thisDiv === null) {
        BuildComment(com.parent);
        thisDiv = document.getElementById(id);
    }

    var comStr = "";
    if (com.content != null) {
        let del = '';
        if (com.name == userName || userStatus > 1) {
            del = '&ensp;<a href="#" onclick="javascript:DeleteCom(\'' + id + '\');return false;">delete</a>';
        }
        let mod = '';
        if (com.status == 0 && userStatus > 1) {
            mod = '&ensp;<span><a href="#" onclick="javascript:Approve(\'' + id + '\');return false;">approve</a></span>';
        }

        let timeStr = TimeString(parseInt(com.date));
        var arr = [com.name,com.name,com.score,
            "s",com.content,board,id,id,
            id,id,del,mod,timeStr];
        comStr = ArrayReplace(comTemp, arr);
    }

    for (i in com.child) {
        comStr += '<div class="comment" id="' + com.child[i] + '"></div>';
    }
    if (com.parent == null) {
        thisDiv = document.getElementById("main");
        com.alt = 0;
    } else {
        com.alt = comments[com.parent].alt ^ 1;
    }
    if (com.alt) {
        thisDiv.style.backgroundColor = '#f8f8f8';
    } else {

    }
    thisDiv.innerHTML = comStr;
    com.built = true;
}

function ShowReply(id, hide = false) {
    var rb = document.getElementById(id).getElementsByClassName("replybox")[0];
    if (hide == false) {
        rb.style.display = 'block';
        rb.getElementsByTagName('textarea')[0].focus();
    } else {
        rb.style.display = 'none';
    }
}

function ArrayReplace(str, arr) {
    for (let i = 0; i < arr.length; i++) {
        str = str.replace('{' + i.toString() + '}', arr[i]);
    }
    return str;
}

function PostReply(id) {
    ShowReply(id, true);
    var tb = document.getElementById(id).getElementsByTagName('textarea')[0];
    var replyText = tb.value;
    tb.value = '';
    PostRequest(boardPath + "submit.php",
            "type=reply" + 
            "&replyText=" + encodeURIComponent(replyText) + 
            "&id=" + id,
            AddComment);
}

function AddComment(resp) {
    if (resp.trim() == 'error') {
        alert('Comment could not be posted.');
        return;
    }
    var respArr = JSON.parse(resp);
    var newId = respArr[0];
    comments[newId] = respArr[1];

    var id = respArr[1].parent;
    var rb = document.getElementById(id).getElementsByClassName("replybox")[0];
    var newCom = document.createElement('div');
    newCom.className = "comment";
    newCom.id = newId;
    rb.parentNode.insertBefore(newCom, rb.nextSibling);
    BuildComment(newId);
}

function DeleteCom(id) {
    var res = confirm('Are you sure you want to delete this comment?');
    if (res == true) {
        var com = document.getElementById(id).getElementsByClassName('comcontent')[0];
        com.innerText = '[deleted]';
        PostRequest(boardPath + "submit.php",
                "type=delete" + 
                "&id=" + id,
                DeleteResponse);
    }
}

function DeleteResponse(resp) {
    if (resp.trim() == 'ok') {

    } else {
        alert("error deleting comment");
    }
}

function Approve(id) {
    var button = document.getElementById(id).getElementsByTagName("span")[0];
    button.innerHTML = "";
    PostRequest(boardPath + "submit.php",
            "type=approve" + 
            "&id=" + id,
            ApproveResp);
}

function ApproveResp(resp) {
    if (resp.trim() == 'ok') {

    } else {
        alert("error approving comment");
    }
}

function PostRequest(url, params, func) {
    var http = new XMLHttpRequest();
    http.onreadystatechange = function() {
        if (http.readyState == 4 && http.status == 200) {
            func(http.responseText);
        }
    };
    http.open("POST", url, true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.send(params);
}

function TimeString(date) {
    var curTime = Math.floor(Date.now() / 1000);
    var elap = curTime - date;

    if (elap > 31103999) {
        elap = elap / 31104000;
        var str = "year";
    } else if (elap > 2591999) {
        elap = elap / 2592000;
        var str = "month";
    } else if (elap > 86399) {
        elap = elap / 86400;
        var str = "day";
    } else if (elap > 3599) {
        elap = elap / 3600;
        var str = "hour";
    } else if (elap > 59) {
        elap = elap / 60;
        var str = "minute";
    } else {
        var str = "second";
    }

    elap = Math.floor(elap);

    if (elap != 1) {
        str += 's';
    }

    return elap.toString() + ' ' + str + ' ago';
}
