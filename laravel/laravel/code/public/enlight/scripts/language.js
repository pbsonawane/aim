/*
Author      : Shadab Khan
Description : The trans method accepts the key of the translation and the attributes what we want to replace, but it's optional.
Refrence    : trans('auth.failed');
		      These credentials do not match our records.		   
*/

function trans(key, replace = {})
{
    let translation = key.split('.').reduce((t, i) => t[i] || null, window.lang_trans_js);

    if (translation) 
    {
        for (var placeholder in replace) 
        {
            if(placeholder != "" && translation.indexOf(":"+placeholder) > -1) translation = translation.replace(`:${placeholder}`, replace[placeholder]);
            else translation = translation.replace("{"+placeholder+"}", replace[placeholder]);
        }
    }
    else
    {
        translation = key;
    }
    
    return translation;

}