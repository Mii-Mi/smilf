// <![CDATA[
    function showSpoiler(obj)
    {
    var inner = obj.parentNode.getElementsByTagName("div")[0];
    if (inner.style.display == "none")
    inner.style.display = "";
    else
    inner.style.display = "none";
    }
    // ]]>

    /* 
    
    ### HTML TO INSERT ###

    <div class="spoiler"><br /> <!-- js/spoiler.js -->
	    <input class="boutonSpoiler" onclick="showSpoiler(this);" type="button" value="Voir-Cacher la shoutbox" /> 
		<div class="inner" style="display: none">
		    <iframe class="shoutbox" name="shoutbox" src="https://chat.smilf.cf/channel/Shoutbox"></iframe>
		</div>
    </div>
    
    */