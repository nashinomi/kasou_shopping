<?php
/*
 * 		コンテント部分のパーツ
 */

//	カテゴリー別並び替え処理
function categorysort($c_table, $cid){
	echo '<select name="cID">';
	echo '<option value="0">すべて</option>';

	for($i = 0; $i < count($c_table); $i++){
		if($cid== $c_table[$i]['categoryid']){
			echo "<option value=".$c_table[$i]['categoryid']." selected>".$c_table[$i]['categoryname']."</option>";
		}else{
			echo "<option value=".$c_table[$i]['categoryid'].">".$c_table[$i]['categoryname']."</option>";
		}
	}
	echo '</select>';
}


//	グッズソート処理
function goodssort($Order){
echo '<select name="Order">';
$goodsorder= array("商品順", "商品逆順", "値段が安い順","値段が高い順","メーカー順","メーカー逆順");
$sqlOrder='';

for($i = 0; $i < count($goodsorder); $i++){
	if($i== $Order){
		echo "<option value=$i selected>".$goodsorder[$i]."</option>";
		switch($Order){
			case 0: $sqlOrder= " ORDER BY goods.goodsID "; break;
			case 1: $sqlOrder= " ORDER BY goods.goodsID DESC "; break;
			case 2: $sqlOrder= " ORDER BY goods.price "; break;
			case 3: $sqlOrder= " ORDER BY goods.price DESC "; break;
			case 4: $sqlOrder= " ORDER BY goods.makerid "; break;
			case 5: $sqlOrder= " ORDER BY goods.makerid DESC "; break;
			default: break;
		}
	}else{
		echo "<option value=$i>".$goodsorder[$i]."</option>";
	}
}
echo '</select>';
return $sqlOrder;
}

//	受け取ったグッズテーブルを一覧表示する
function goodsListview($g_table, $itemCount, $cID, $Order){
	$rimit= $itemCount+19;
	echo '<table>';
	$row= count($g_table);
	for(; $itemCount< $row; ++$itemCount){
		if($itemCount > $rimit){
			$rimit= $row-$itemCount;
			if($rimit > 20){	$rimit=20;	}
			echo '<tr>';
			echo '<td colspan="2"><a href="listGoods.php?cID='.$cID.'&Order='.$Order.'&itemCount='.$itemCount.'">次の'.$rimit.'件を表示する</a></td>';
			echo '</tr>';
			break;
		}

		echo '<tr>';
		echo '<td>'.($itemCount+1).'</td>';
		echo '<td>ID:'. $g_table[$itemCount]['goodsid'].'</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td id="tableCreatematch_bottom" rowspan="3" align="center">';
		echo '<a href="goodsView.php?goodsid='.$g_table[$itemCount]['goodsid'].'"><img src="'.$g_table[$itemCount]['filepass'].'"></a>';
		echo '</td>';
		echo '<td>';
		echo '商品名：<a href="goodsView.php?goodsid='.$g_table[$itemCount]['goodsid'].'">'.$g_table[$itemCount]["goodsname"].'</a>';
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>メーカー： '.$g_table[$itemCount]["makername"].'</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td id="tableCreatematch_bottom">価格： '. number_format($g_table[$itemCount]["price"]).'円</td>';
		//echo '<td id="tableCreatematch_bottom">';
		//echo '<input type="button" onClick="location.href="./addCart.php?goodsid='.$g_table[$itemCount]['goodsid'].'" value="カートに入れる" />';
		//echo '</td>';
		echo '</tr>';
	}
	echo '</table>';
}

//	グッズの詳細情報表示
function goodsInfo($g_table){
	echo '<div class="goodsimage"><img src=" '.$g_table[0]['filepass'].'" /></div>';
	echo '<h4><strong>'.$g_table[0]['goodsname'].'</strong></h4>';
	echo '<p class="goodsmaker"><span>'.$g_table[0]['makername'].'</span></p>';
	echo '<hr>';
	echo '<p class="goodsright"><span>残数： </span>'.$g_table[0]['stock'].'</p>';
	echo '<p class="goodsright"><span>価格：</span><strong>￥'.number_format($g_table[0]['price']).'</strong></p>';
	echo '<input type="button" onClick="location.href='."'../Controller/GoodsMaintenanceMVC.php?goodsid=".$g_table[0]['goodsid']."&controll=". 4 ."'".'" value="カートに入れる" class="infocart"/>';
	echo '<p>備考</p>';
	echo '<hr>';
	echo '<p class="goodsnotes">'.$g_table[0]['notes'].'</p>';
}

/*
 * おすすめ商品として同一カテゴリーをシャッフル表示する
 */
function goodsMatchcategory($g_table, $row){

	//	null回避
	if($g_table!=null){
		if(!shuffle($g_table)){
			echo "シャッフル失敗";
		}
	}
	echo '<table class="categoryMatchtable">';
	//	配列の要素数を取得し20よりも多ければ制限をつける
	if($row > 20){
		$row= 20;
	}
	for($i=0; $i< $row; $i+=2){
		if($i+2 <=$row){
			echo '<tr>';
			echo '<td id="tableCreatematch_bottom" rowspan="3">';
			echo '<a href="goodsView.php?goodsid='.$g_table[$i]['goodsid'].'"><img src="'.$g_table[$i]["filepass"].'"></a>';
			echo '</td>';
			echo '<td id="tableCreatematch_right">商品名：<a href="goodsView.php?goodsid='.$g_table[$i]['goodsid'].'">'.$g_table[$i]["goodsname"].'</a></td>';
			echo '<td id="tableCreatematch_bottom" rowspan="3">';
			echo '<a href="goodsView.php?goodsid='.$g_table[$i+1]['goodsid'].'"><img src="'.$g_table[$i+1]["filepass"].'"></a></td>';
			echo '<td>商品名： <a href="goodsView.php?goodsid='.$g_table[$i+1]['goodsid'].'">'.$g_table[$i+1]["goodsname"].'</a></td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td id="tableCreatematch_right">メーカー： '.$g_table[$i]["makername"].'</td>';
			echo '<td>メーカー： '.$g_table[$i+1]["makername"].'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td id="tableCreatematch_bottom_right">値段：'.number_format($g_table[$i]["price"] ).'円</td>';
			echo '<td id="tableCreatematch_bottom">値段: '.number_format($g_table[$i+1]["price"]).'円</td>';
			echo '</tr>';
		}else{
			echo '<tr>';
			echo '<td id="tableCreatematch_bottom" rowspan="3">';
			echo '<a href="goodsView.php?goodsid='.$g_table[$i]['goodsid'].'"><img src="'.$g_table[$i]["filepass"].'"></a></td>';
			echo '<td id="tableCreatematch_right">商品名：<a href="goodsView.php?goodsid='. $g_table[$i]['goodsid'].'">'.$g_table[$i]["goodsname"].'</a></td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td id="tableCreatematch_right">メーカー： '.$g_table[$i]['makername'].'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td id="tableCreatematch_bottom_right">値段：'.number_format($g_table[$i]["price"] ).'円</td>';
			echo '</tr>';
		}
	}
	if($i==0){
		echo "<tr><th>この商品以外に同じカテゴリーの商品をあつかっておりません。</th></tr>";
	}
	echo '</table>';
}

/*
 * 	カート表示
 */
function cartInfo($ca_table){
	echo '<form method="POST" name= "stockform" onSubmit="return checkSelected()" action="../Controller/GoodsMaintenanceMVC.php" >';
	echo '<input type="hidden" name="controll" value="5" />';
	echo '<input type="submit" value="数量の更新" style="width:120px" >';
	echo '<table>';
	echo '<tr class="infoTable_head">';
	echo '<th colspan="2">商品</th>';
	echo '<th>価格</th>';
	echo '<th>数量</th><th></th>';
	echo '</tr>';
	$itemCount=1;
	$row=count($ca_table);

	for($i = 0; $i < $row; $i++){
		$totalprice=$ca_table[$i]['amount']*$ca_table[$i]['price'];
		$allprice+=$totalprice;
		echo '<input type="hidden" name="'. 'goodsid'.$i .'" value='.$ca_table[$i]['goodsid'].'>';

		echo '<tr>';
		echo '<td colspan="2" class="infoTable_main">';
		echo '<a href="goodsView.php?goodsid='.$ca_table[$i]['goodsid'].'"><b>'. $ca_table[$i]['goodsname'].'</b></a><br /><br />';
		echo '<span>'.$ca_table[$i]['makername'].'</span></td>';
		echo '<td class="infoTable_sub"><b>￥'.number_format($totalprice).'円</b></td>';
		echo '<td class="infoTable_sub">';
		echo '<select  style="width:50" name='. 'stock'.$i. '>';
		//	ストック
		for($j = 1; $j <= $ca_table[$i]['stock']; $j++){
			if($ca_table[$i]['amount']==$j){
				echo "<option value=$j selected>$j</option>";
			}else{
				echo "<option value=$j>$j</option>";
			}
		}
		echo '</select>';
		echo '</td>';
		echo "<td class='infoTable_sub'>";
		echo '<input type="button" onClick="location.href='."'../Controller/GoodsMaintenanceMVC.php?goodsid=".$ca_table[$i]['goodsid']."&controll=". 6 ."'".'" value="削除" />';
		echo '</td></tr>';



		if($i+1 == $row){
			echo "<p class='totalPriceCart' >合計金額：　<b>￥ ".number_format($allprice)." 円</b></p>";
		}
		$itemCount++;
	}

	if($itemCount== 1){
		echo "<br><p><b>カゴには何も入っていません。</b></p>";
	}
	echo '</table>';
	echo '</form>';
}
?>