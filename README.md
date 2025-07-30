# QRスタンプラリー_完成
 
実行時はsource_codeフォルダのファイル全てを/usr/share/nginx/html/内に格納し実行した  
データベースはMariaDBを使い、使用したテーブルはdatabaseフォルダ内にあるファイルにダンプした  
ただし、ファイルはテーブルのみなのでデータベース自体はプログラムに合わせて作成する必要がある  
（もしくは、作成したデータベースに合わせてプログラムを変更する）

ファイルの説明

1.souce_code内  
・start_screen.html：スタート画面  
・start_screen.css：スタート画面用cssファイル  
・allmap.html：全体マップ  
・allmap.css：全体マップ用cssファイル  
・app_screen.html：大学の詳細マップ  
・app_screen_v2.css：大学の詳細マップ用cssファイル（変更によりv2）  
・app_database.php：Cookieやデータベース操作用のphpファイル  
・popup.css：ポップアップ用のcssファイル  
・map1.PNG：大学の詳細マップ画像  
・spot_n.jpg：N棟のポップアップ用画像  
・spot_s_2.jpg：S棟1（空の木）のポップアップ用画像  
・spot_s_4.jpg：S棟2（フリーコモンズ）のポップアップ用画像  
・spot_w.jpg：W棟のポップアップ用画像  
・end.png：スタンプを全て達成した時用の画像  

2.database内  
・qr_database_dump.sql：MariaDBからダンプしたQRコードの状況を記録するテーブルのダンプファイル  

3.QRcode  
・QRコード_まとめ.pptx：各棟のQRコードが書かれたファイル  
