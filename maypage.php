<?php
session_start();
include("funcs.php");
$id = filter_input( INPUT_GET, "id" );

$pdo = db_conn();
sschk();

$stmt = $pdo->prepare("SELECT * FROM gs_user_table WHERE id=:id");
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$status = $stmt->execute();

if ($status == false) {
    sql_error($stmt);
} else {
    $row = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP:400,700&amp;subset=japanese" rel="stylesheet">
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.18.1/styles/monokai-sublime.min.css">
<link rel="stylesheet" href="css/index.css">
<link rel="stylesheet" href="css/mybook.css">
<link href="css/paging.css" rel="stylesheet">
<link rel="stylesheet" href="css/user_detail.css">
<link href="https://unpkg.com/sanitize.css" rel="stylesheet"/>
<title>テスト画面（マイページ）</title>
<link rel="shortcut icon" href="img/icon.png"/>
<script src="https://unpkg.com/feather-icons"></script>
<style>
.m-form-item-textarea textarea {
  height: 300px;
}
.m-form-textarea {
  display: block;
  width: 100%;
  padding: 5px 10px;
  border-radius: 4px;
  border: none;
  box-shadow: 0 0 0 1px #ccc inset;
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  resize: vertical;
}
.m-form-textarea:focus {
  outline: 0;
  box-shadow: 0 0 0 2px rgb(33, 150, 243) inset;
}
</style>
</head>
<body>

<main id="main">

<form method="post" action="user_update.php" enctype="multipart/form-data">
  <div class="content">
    <div class="main" style="margin-bottom:30px; display:flex;">
      <div data-feather="user" style="color:#2EB9C6;" width="30" height="30"></div>
      <h1 style="color:#6F7681; margin-left:5px;">ユーザー情報変更</h1>
    </div>
    <label for="username">現在のプロフィール画像</label>
    <?php if ($row["img"] == "2"): ?>
    <?php echo
    '<div class="prof_hover"><img class="prof_img" src="/codestock/img/noimage.png" style="margin-bottom:50px;" width="80" height="80"></img></div>'
    ?>
    <?php else: ?>
    <?php echo
    '<img class="prof_img" src="/codestock/user_upload/'.$row["img"].'" style="margin-bottom:50px;" width="80" height="80"></img>'
    ?>
    <?php endif;?>

    <label class=""for="profile">自己紹介（スキルなど）</label>
    <textarea name="profile" class="m-form-textarea" rows="20" style="margin-bottom:30px; resize: none;"><?php echo $row["profile"];?></textarea>

    <label for="username">カラーテーマーを選択</label>
    <label for="username">①ライトブルー</label>
    <label for="username">②ピンク</label>
    <select id="code_sentaku" type="text" name="color_flg" style="margin-bottom:30px;">
      <option selected><?php $row["color_flg"]; ?></option>
      <option value="0">ライトブルー</option>
      <option value="1">ピンク</option>
    </select>

    <label for="username">ユーザー名</label>
    <input id="username" type="text" name="name" style="margin-bottom:30px;" value="<?php echo $row["name"]; ?>">

    <label for="email">メールアドレス</label>
    <input id="email" type="text" name="lid" style="margin-bottom:30px;" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" value="<?php echo $row["lid"]; ?>">

    <label for="password">パスワード</label>
    <input id="password" type="text" name="lpw" pattern="^([a-zA-Z0-9]{6,})$" placeholder="変更あるときだけ入力してください。">
    <div style="margin-bottom:30px;"><font color="#0000ff">半角英数字6文字以上で入力ください。</font></div>

    <label for="userpic">プロフィール写真の変更</label>
    <input type="file" name="upfile" id="myImage" accept="image/*" style="margin-bottom:20px;">
    <img id="preview" class="prof_img" width="80" height="80" border="0">

    <label style="margin-bottom:30px;">※ 退会する場合はこちらにチェックしてください。</label>
      <?php if($row["life_flg"]=="0"){ ?>
          利用中<input id="taikai" type="radio" name="life_flg" value="0" checked="checked">
          退会<input id="taikaibtn" type="radio" name="life_flg" value="1">
      <?php }else{ ?>
          利用中<input type="radio" name="life_flg" value="0">
          退会<input id="taikaibtn" type="radio" name="life_flg" value="1" checked="checked">
      <?php } ?>
    <input type="hidden" name="owner_flg" value="0">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <input style="margin-top:30px;" class="btn" type="submit" value="更 新">
  </div>
</form>


</main>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://unpkg.com/feather-icons"></script>
<script>

//featherアイコン
feather.replace();

$('#myImage').on('change', function (e) {
    var reader = new FileReader();
    reader.onload = function (e) {
        $("#preview").attr('src', e.target.result);
    }
    reader.readAsDataURL(e.target.files[0]);
});
$("#logout_btn").on("click", function(){
  if (!confirm('ログアウトしますか？')) {
    return false;
  } else {
    $("#push").append("ログアウトしました");
  }
});
$("#taikaibtn").on("click", function(){
  if (!confirm('本当に退会しますか？')) {
    return false;
  } else {
    $(".btn").on("click", function(){
      alert("ご利用ありがとうございました。")
    })
  }
});


//登録ボタンをクリック
$("#btn").on("click",function() {
    //axiosでAjax送信
    //Ajax（非同期通信）
    const params = new URLSearchParams();
    params.append('url',  $("#url").val());
    params.append('naiyou', $("#naiyou").val());

    //axiosでAjax送信
    axios.post('mybook_insert.php',params).then(function (response) {
        console.log(typeof response.data);//通信OK
        if(response.data==true){
          document.querySelector("#status").innerHTML=response.data;
        }
    }).catch(function (error) {
        console.log(error);//通信Error
    }).then(function () {
        console.log("Last");//通信OK/Error後に処理を必ずさせたい場合
    });
});
</script>
</body>
</html>
