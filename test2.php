<!DOCTYPE html>
<html>
<head>
<style>
.cont {
    background-color: #ddd;
    margin: 10px 0px 2px 10px;
    padding: 10px 2px 2px 10px;
    border: 1px solid #777;
}
</style>
<script type="text/javascript">
<?php
$root = $_SERVER['DOCUMENT_ROOT'];
include($root . '/board/model/db.php');
include($root . '/board/model/user.php');
$db = new DB;
$user = new User($db);
$user->TryVerify();

$curPost = 'S89vqaTiXSq8ocBN';
$postArr = $db->GetPost($curPost, '1');
echo "var com = " . json_encode($postArr) . ";\n";
echo "var curPost = '" . $curPost . "';\n";
?>
window.onload = function() {
    var str = "";
    for (c in com) {
        var cur = com[c];
        if (cur.parent != null) {
            par = com[cur.parent];
            if (par.child == null) {
                par.child = new Array();
            }
            par.child.push(c);
        }
        cur.built = false;
    }

    BuildComment('jWipgksyR9yu1PbB');
    for (c in com) {
        BuildComment(c);
    }
}

function BuildComment(id) {
    var comment = com[id];

    if (comment.built == true) {
        return;
    }

    var thisDiv = document.getElementById(id);
    if (thisDiv === null) {
        BuildComment(comment.parent);
        thisDiv = document.getElementById(id);
    }

    var comStr = comment.content;
    for (i in comment.child) {
        comStr += '<div class="cont" id="' + comment.child[i] + '"></div>';
    }
    if (thisDiv === null) {
        alert("Its null");
    }
    thisDiv.innerHTML = comStr;
    comment.built = true;
}
</script>
</head>
<body>
<div id="test">
</div>
<div class="cont" id="<?php echo $curPost ?>">
</div>
</body>

</html>
