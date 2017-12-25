# Live2D WordPress Plugin

Cubism SDK For JavaScript を WordPress で動かすためのプラグインです。

簡単な手順でブログ上に Live2D モデルを表示することができます。

WordPress の管理画面から操作ができるので、プログラムの知識は必要ありません。

## インストールするには？

1. WordPress の wp-content/plugins ディレクトリに live2d フォルダごとアップロードします

2. 管理画面から "Live2D WordPress Plugin" を有効化します

## オリジナルのモデルを使うには？

1. assets フォルダの moc3 や json、テクスチャを差し替えます

2. WordPressの管理画面を開き、サイドメニューから Live2D Settings を開きます

3. "Model path" などの入力項目を、差し替えた各ファイルへのパスに変更します

4. 「変更を保存」ボタンを押して入力項目の内容を保存します

## モデルの位置やサイズを変更するには？

1. WordPress の管理画面を開き、サイドメニューから Live2D Settings を開きます

2. "Attach tag" をモデルの位置の基準にしたいタグに変更します

3. "Possition x" "Possition y" を基準にしたいタグからの相対位置に変更します

4. "Scale" を任意のサイズに変更します

5. 「変更を保存」ボタンを押して入力項目の内容を保存します

## 機能

- マウスカーソルを視線追従する機能

- マウスクリックでのモーション切り替え機能

## 注意事項

視線追従機能を有効にするために、 live2dcubismframework.js の Animation.prototype.evaluate 関数内で、"PARAM_ANGLE_X" "PARAM_ANGLE_Y" "PARAM_EYE_BALL_X" "PARAM_EYE_BALL_Y" のパラメータを無効化しています。

モデルの設計に応じて無効化するパラメータは変更してください。

## ライセンス

このプラグインは GNU General Public License, version 2 ライセンスで提供しています。

また、 Live2D Cubism Core は Live2D Proprietary Software License ライセンスとなっています。

詳細については、下記のライセンスを確認してください。

This plugin is under GNU General Public License, version 2 and additional permission.
For legal details, be sure to check GNU General Public License, version 2 and Live2D Proprietary Software License.

[GNU General Public License, version 2](https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)

[Live2D Proprietary Software License](http://live2d.com/eula/live2d-proprietary-software-license-agreement_en.html)
