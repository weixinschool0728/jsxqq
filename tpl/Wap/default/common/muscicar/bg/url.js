function setUrlParam(para_name,para_value,url)
{
        var strNewUrl=new String();
        var strUrl=url;
        //alert(strUrl);
        if(strUrl.indexOf("?")!=-1)
        {
            strUrl=strUrl.substr(strUrl.indexOf("?")+1);
            //alert(strUrl);
            if(strUrl.toLowerCase().indexOf(para_name.toLowerCase())==-1)
            {
                strNewUrl=url+"&"+para_name+"="+para_value;
                return strNewUrl;
            }else
            {
                var aParam=strUrl.split("&");
                //alert(aParam.length);
                for(var i=0;i<aParam.length;i++)
                {
                    if(aParam[i].substr(0,aParam[i].indexOf("=")).toLowerCase()==para_name.toLowerCase())
                    {
                       aParam[i]= aParam[i].substr(0,aParam[i].indexOf("="))+"="+para_value;
                    }
                }
               
               strNewUrl=url.substr(0,url.indexOf("?")+1)+aParam.join("&");
              // alert(strNewUrl);
               return strNewUrl;
           }
            
        }else
        {
            strUrl+="?"+para_name+"="+para_value;
            //alert(strUrl);
            return strUrl
        }
}