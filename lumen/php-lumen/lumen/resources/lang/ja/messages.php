<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Locale Language codes with their names
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default language name
    | Here, Numbers are considered as keywords.
    |
    */
    /*
	*	
     | odd number for fail message
     | even number for success message
     | code range from -01 to -10. example: 101-110, 201-210
    */
    

        101 => '{名前}レコードが見つかりません。',
        102 => '{名前}レコードが見つかりました。',
        103 => '{name}は正常に作成されませんでした。',
        104 => '{name}が正常に作成されました。',
        105 => '{name}は正常に更新されませんでした。',
        106 => '{name}は正常に更新されました。',
        107 => 'エンティティはユーザーに関連していません。',
        108 => 'エンティティはユーザーに正常に関連付けられています。',
        109 => '入力された詳細は存在しません。',
        110 => '入力された詳細は正常に検証されました。',
        111 => '入力した詳細が正しくありません。有効な詳細を入力してください。 ',
        113 => 'トークンが見つかりません',
        115 => '提供されたトークンは期限切れです。',
        117 => 'トークンのデコード中にエラーが発生しました。',
        118 => '{name}は正常に削除されました。',
        119 => '{name}は正常に削除されませんでした。',
        121 => 'レコードの削除に失敗しました。 {name}との関係があるように。 ',
        123 => '{名前}が必要です。',
        124 => '有効なパスワード',
        125 => '無効なパスワード。',
        126 => 'パスワードのリセット要求は正常に送信されました。リンクをリセットするにはメールボックスをチェックしてください。リンクは{名前} です',
        127 => '入力されたユーザーは存在しません。',
        129 => 'メールは送信されません。もう一度お試しください。 ',
        130 => 'パスワードは正常にリセットされました。ログインしてください。',
        131 => 'リンクの有効期限',
        132 => 'リンクは有効です。',
        133 =>'ホワイトリストに登録されたIPには少なくとも1つのIPが必要です。',
        134 => '{name}は正常にホワイトリストに登録されました。',
        135 => '{name}はすでにホワイトリストに登録されています。',
        137 => '不正なパブリックIPアドレスからアカウントにアクセスしようとしています。この新しいパブリックIPアドレスを承認するために,登録したメールアドレスへのリンクを送信しました。このパブリックIPアドレスをホワイトリストに登録するには,そのリンクをクリックしてください。 {名}',
        138 => '{name}承認に成功しました。',
139 => '{name}はユーザーに正常に割り当てられました。',
        140 => '{name}はユーザーに正常に割り当てられていません。',
        142 => '{name}は正常に終了しました。',
        143 => '無効なトークン',
        144 => '{name}が正常にアップロードされました。',
        145 => '{name}のアップロードに失敗しました。',
        100 => 'アクセスが拒否されました。無効なユーザーにアクセスしようとしています。 ',
        
		200 => 'アクセスが拒否されました。無効なエンティティにアクセスしようとしています。 ',
        300 => 'パスワードフィールドには,少なくとも1つの小文字,大文字,数字を含める必要があります',
        500 => 'IP Changed',
        600 => '{name}ユーザーは正常にログインしました',
        700 => '{name}ユーザーは正常にログアウトしました',
		800 => '{name}',
        900 => '不正アクセス',
        
		'000' => '{name}フィールドは必須です',

        //For Descriptions: html_tags_not_allowed
        '001' => '{name} HTMLタグは許可されていません。',
        
        //For Key: key_value
        '002' => '{name}大文字とアンダースコアのみ使用できます',

        // For Name: allow_alpha_numeric_space_dash_underscore_only
        '003' => '{name}には英数字,スペース,ダッシュ,アンダースコアのみ使用できます',

        // For unique
        '004' => '{name}は既に存在します',

         '005' => '{name}は存在しません',
   
        '006' => '{name}は既に使用されています',

   
        '007' => '{name}には英数字,ダッシュ,アンダースコアのみ使用できます',
      
        '008' => '{name} JSONを生成するには,[JSONの生成]をクリックします',		
        '009' => '{name} のアルファベットとスペースのみが許可されています。',
     
        '010' => '{name}に無効なIPがあります。',
       
        '011' => '{name}の形式が無効です。',
        '012' => '{name}の形式が無効です。',

        '013' => '{name}無効なIP /サブネット。',

        '014' => '{name}のメール形式が無効です。',

        '015' => '{name}の確認はパスワードと一致する必要があります。',

        // min_length：8
        '016' => 'パスワードは{name}文字以上でなければなりません。',
		
		  '018' => 'パスワードには少なくとも{name}個の小文字を含める必要があります。',

        // min_uppercase_chars：1
        '019' => 'パスワードには少なくとも{name}の大文字を含める必要があります。',

        // disallow_numeric_chars：
        '020' => 'パスワードに数字を含めることはできません。',

        // disallow_numeric_first
        '021' => '最初の文字を数字にすることはできません。',

        // min_numeric_chars：1
        '022' => 'パスワードには少なくとも{name}の数字が必要です。',

        // min_nonalphanumeric_chars：1
        '023' => 'パスワードには少なくとも{name}の非英数字を含める必要があります',

'msg_confirmdelete' => '本当に削除しますか？',
'msg_confirmsuspenduser' => 'このユーザーを一時停止してもよろしいですか？',
'msg_confirmactivateuser' => 'このユーザーをアクティブ化してもよろしいですか？',
'msg_confirmdeleteuser' => 'このユーザーを削除してもよろしいですか？',
'msg_confirmcontinue' => '続行してもよろしいですか？',

        'msg_deleting_location' => '場所の削除',
        'msg_updating_location' => '場所の更新',
        'msg_editing_location' => '場所の編集',
        'msg_adding_location' => '場所の追加',
        'msg_loading_locations' => '場所の読み込み',

        'msg_loading_datacenters' => 'データセンターのロード',
        'msg_adding_datacenter' => 'データセンターの追加',
        'msg_update_datacenter' => 'データセンターの更新',
        'msg_datacenter_edit' => 'データセンター編集',
        'msg_updating_datacenter' => 'データセンターの更新',
        'msg_deleting_datacenter' => 'データセンターの削除',

        // max_length：16
        '017' => 'パスワードは{name}文字未満でなければなりません',
		'msg_recordnotfound' => 'レコードが見つかりません。',
		'msg_recordfound' => 'レコードが見つかりました。',
		'msg_userdeleted' => 'ユーザーが正常に削除されました。',
		'msg_relation_with_dc_regions' => 'レコードを削除できませんでした。データセンター地域/ポッドとの関係がある',
		'msg_record_deleted_successfully' => 'レコードが正常に削除されました',
		'msg_id_required' => 'Idフィールドは必須です',



        //purchase history messages
        "msg_approved"              => "{name} が承認されました.",
        "msg_cancelled"             => "{name} はキャンセルされました.",
        "msg_closed"                => "{name} は閉じられました.",
        "msg_deleted"               => "{name} が削除されました.",
        "msg_item_received"         => "{name} アイテムを受け取りました.",
        "msg_notifyagain"           => "{name} 再度通知します.",
        "msg_notifyowner"           => "{name} 所有者に通知します.",
        "msg_notifyvendor"          => "{name} ベンダーに通知.",
        "msg_open"                  => "{name} が開いています.",
        "msg_partiallyapproved"     => "{name} は部分的に承認されました.",
        "msg_partiallyreceived"     => "{name} は部分的に受信しました.",
        "msg_pendingapproval"       => "{name} 保留中の承認.",  //created
        "msg_rejected"              => "{name} は拒否されました.",

        "msg_created"               => "{name} が作成されました.",
        "msg_updated"               => "{name} が更新されました.",
		
];
