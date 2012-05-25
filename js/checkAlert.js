/*
	アラートメソッド
*/

var qsParm = new Array();

//	Getの値をscriptで得る処理
function retrieveGETqs() {
	var flag=false;
	var query = window.location.search.substring(1);
	var parms = query.split('&');
	for(var i=0; i<parms.length; i++) {
		var pos = parms[i].indexOf('=');
		if(pos > 0) {
			var key = parms[i].substring(0,pos);
			var val = parms[i].substring(pos+1);
			qsParm[key] = val;
			//	値が追加された時にtrueにする
			var flag= true;
		}
	}
	return flag;
}
/*
 * 受け取ったIDのアラート表示
*/
function alret_gID(){
	switch(qsParm['aFlag']){
		case '1': alert("商品ID「"+qsParm['gID']+"」が追加されました。"); break;
		case '2': alert("商品ID「"+qsParm['gID']+"」が更新されました。"); break;
		case '3': alert("商品ID「"+qsParm['gID']+"」が削除されました。"); break;
		default: alert(qsParm['aFlag']); break;
	}
}

/*
 * 受け取った文字が半角数字かをチェックするメソッド
*/
function checkNormal(str){

	for(i = 0; i < str.length; i++){
		var c = str.charAt(i);

		if (c < '0' || c > '9'){
			if(c=='-'){ continue; }		//	ハイフンは例外で
			return true;
		}
	}
	return false;
}
/*
 * 商品IDが正しく入力されているかの判定
*/
function check_gID(gID){

	//ハイフンチェック&半角数字チェック

	if(gID.charAt(3)!='-'){
		return true;
	}else if(checkNormal(gID)){
		return true;
	}else if(gID.length!=9){
		return true;
	}
	return false;
}
/*
 * 価格が正しく入力されているかの判定を行う
*/
function check_Price(Price){
	//
	if (Price.charAt(0)== '0'){
		return true;
	}else if(checkNormal(Price)){
		return true;
	}
	return false;
}

// フォームのチェック処理(追加)
function checkForm_add() {

	var flag= 0;
	var gID= document.addform.gID.value;
	var gunitPrice= document.addform.gunitPrice.value;
	var Price= document.addform.Price.value;

	//	gIDチェック
	if(gID==""){
		alert('必須項目の商品IDが入力されていません。');
		flag= 1;
	}else if(check_gID(gID)){
			alert('商品IDが正しく入力されていません。');
			flag= 1;
	}

	//	gNameチェック
	if(document.addform.gName.value==""){
		alert('必須項目の商品名が入力されていません。');
		flag= 1;
	}

	//	gunitPriceチェック
	if(gunitPrice==""){
		alert('必須項目の単価が入力されていません。');
		flag= 1;
	}else if(check_Price(gunitPrice)){
		alert('単価が正しく入力されていません。');
		flag= 1;
	}

	//	integer限界設定
	if((gunitPrice*1.5)> 100000000 || Price> 10000000){
		alert('価格を１億円以上には設定できません。');
		flag= 1;
	}

	// リターン判定
	if(flag== 1){
		return false;
	}else{
		return true;
	}
}

function checkRetrival(){
	var key= document.retrival.retrievalKey.value;
	if(!key){
		return false;
	}
	return true;
}


/*
 * 	addUser.php
 * 	値をPOSTで飛ばす
 */
function checkuserid_p(){
	var obj=document.menberform;
	obj.action="";

	var id= document.menberform.userid.value;
	if(id!=''){
		if(AlphabetCheck(id)){
			//	現在入力されている値をobjのエレメントに格納してsubmit
			obj.userid.value=id;
			obj.username.value= document.menberform.username.value;
			obj.submit();
		}else{
			document.getElementsByName("userid").item("index").focus();
			alert("IDは半角英数字のみで入力して下さい。");
			return false;
		}
	}else{
		document.getElementsByName("userid").item("index").focus();
		alert('IDが入力されていません。');
		return false;
	}
}

/*
 * addUser.php
*  入力されている値をGETとして飛ばす
*/


function checkuserid(){
	var id= document.menberform.userid.value;
	if(id!=''){
		if(AlphabetCheck(id)){
			location.href="adduser.php?checkid="+id;
		}else{
			document.getElementsByName("userid").item("index").focus();
			alert("IDは半角英数字のみで入力して下さい。");
			return false;
		}
	}else{
		document.getElementsByName("userid").item("index").focus();
		alert('IDを入力してください。');
		return false;
	}
}

/*
 * 英数字のみかのチェック(正規表現)
 */
function AlphabetCheck(key){
	if( key.match( /[^A-Za-z\s.-]+/ ) && key.match( /[^0-9]+/ ) ){
		return false;
	}
	return true;
}


/*
 * カート数量がデフォルトの値から変更されたか判断する
 */

function checkSelected(){
	var temp;
	//	エレメント総数を得るdocment
	var elementMax= document.stockform.elements.length
	//	エレメント数 -SelectNameは3つ置きにでてくる-(動的な処理では無い)
	var i= 3;	//	カートフォームに限定された数値

	while(i< elementMax){
		temp= document.stockform.elements[i].value;
		//	optionsの値はoptionタグの数。0～からのカウントなのでvalue値が1～スタートを差し引き1を設定する
		if(!document.stockform.elements[i].options[temp-1].defaultSelected){
			//alert('数量の変更確認しました');
			return true;
		}
		i+=3;
	}
	alert('数量は変更されていません。');
	return false;
}

// Twitter
function twitter(){
}
