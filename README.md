# Live2D WordPress Plugin

Cubism SDK For JavaScript を WordPress で動かすためのプラグインです。

WordPress のブログ上に Live2D モデルを表示することができます。

管理画面が付いているので手軽に編集操作が行えます。

## インストールするには？

- WordPress のインストールについては [WordPress Codex 日本語版](http://wpdocs.osdn.jp/Main_Page) の [WordPress のインストール](http://wpdocs.osdn.jp/WordPress_%E3%81%AE%E3%82%A4%E3%83%B3%E3%82%B9%E3%83%88%E3%83%BC%E3%83%AB) を参照してください



1. [トップページ](https://github.com/Live2D/CubismWordPressPlugin) の右上にある "Clone or download" ボタンを押します

2. ポップアップが表示されるので、"Download ZIP" ボタンを押します

3. ダウンロードした zip ファイルを解凍します

4. 解凍した live2d フォルダを WordPress の plugins ディレクトリにアップロードします

5. WordPress の管理画面から "Live2D WordPress Plugin" を有効化します

6. ブログ上にデフォルトのモデルが表示されます

## オリジナルのモデルを使うには？

1. assets フォルダの moc3 や json、テクスチャをオリジナルのモデルに差し替えます

2. WordPress の管理画面を開き、サイドメニューから Live2D Settings を開きます

3. "Model path" などの入力項目を、差し替えた各ファイルへのパスに変更します

4. 「変更を保存」ボタンを押して入力項目の内容を保存します

5. オリジナルのモデルが表示されるようになります

## モデルの位置やサイズを変更するには？

1. WordPress の管理画面を開き、サイドメニューから Live2D Settings を開きます

2. "Attach tag" をモデルの位置の基準にしたいタグに変更します

3. "Possition x" "Possition y" を基準にしたいタグからの相対位置に変更します

4. "Scale" を任意のサイズに変更します

5. 「変更を保存」ボタンを押して入力項目の内容を保存します

6. 変更後の位置やサイズがモデルに反映されます

## 機能

- マウスカーソルを視線追従する機能

- マウスクリックでのモーション切り替え機能

## 注意事項

視線追従機能を有効にするために、下記パラメータの更新を無効化しています。

- PARAM_ANGLE_X
- PARAM_ANGLE_Y
- PARAM_EYE_BALL_X
- PARAM_EYE_BALL_Y
- ParamAngleX
- ParamAngleY
- ParamEyeBallX
- ParamEyeBallY

モデルの設計に応じて下記のファイルを編集してください。

- live2dcubismframework.js (Animation.prototype.evaluate 関数内)
- pixiWordPressPlugin.js

## ライセンス
ライセンスについては下記をご確認ください。

- live2d.php は GNU General Public License, version 2 で提供しています。
 - [GNU General Public License, version 2](https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
   - live2d.php


- Live2D Cubism Core は Live2D Proprietary Software License で提供しています。
 - Live2D Proprietary Software License 
[日本語](http://www.live2d.com/eula/live2d-proprietary-software-license-agreement_jp.html) 
[English](http://www.live2d.com/eula/live2d-proprietary-software-license-agreement_en.html) 
   - live2dcubismcore.min.js


- Live2D Cubism Components は Live2D Open Software License で提供しています。
 - Live2D Open Software License 
[日本語](http://www.live2d.com/eula/live2d-open-software-license-agreement_jp.html) 
[English](http://www.live2d.com/eula/live2d-open-software-license-agreement_en.html) 
   - live2dcubismframework.js
   - live2dcubismpixi.js
   - pixiWordPressPlugin.js


- サンプルモデルは Free Material License で提供しています。
 - Free Material License 
[日本語](http://www.live2d.com/eula/live2d-free-material-license-agreement_jp.html) 
[English](http://www.live2d.com/eula/live2d-free-material-license-agreement_en.html) 
   - assets/Koharu/*
   - assets/Hiyori/*




