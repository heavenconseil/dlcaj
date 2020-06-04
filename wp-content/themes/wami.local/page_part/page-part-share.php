<?php 
	$titre_share = get_the_title($post->ID);	
	$content_share = strip_tags( get_the_content() );
	if(strlen($content_share) > 39){
		$content_share = substr($content_share, 0, 39);
		$content_share = substr($content_share, 0, strrpos($content_share," "))." ...";
	}
	$link_share = get_permalink($post->ID);
	$image_share = get_the_post_thumbnail_url( $post->ID, 'paysage_big' ); //wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
?>
<div id="share-annonce">
    <h3>Partager</h3>
    <ul id="rs">
        <!-- <li>
        	<div id="fb-root"></div>
			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.9&appId=234559506591632";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
			<div class="fb-share-button" data-href="<?php get_permalink(); ?>" data-layout="button_count">
				<a class="rs-link rs-fb" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo addslashes($link_share); ?>&src=sdkpreparse&caption=<?php echo addslashes($image_share); ?>&picture=<?php echo addslashes($image_share); ?>"><span class="fa fa-facebook"></span></a>
			</div>
        </li> -->
        <li>
            <div id="fb-root"></div> 
            <script type="text/javascript">
                window.fbAsyncInit = function(){
                    FB.init({
                        appId  :'1896527210399452', //prod : '420687668293938',
                        status : true,
                        cookie : true,
                        xfbml  : true
                    });
                };
                function postToFeed(){
                    //alert(slug);
                    FB.ui({
                        method: 'feed',
                        name: '<?php echo addslashes($titre_share); ?>',
                        caption: '',
                        description: '<?php echo addslashes($content_share); ?>',
                        link: '<?php echo addslashes($link_share); ?>',
                        picture: '<?php echo addslashes($image_share); ?>'
                    });
                };
                (function(d){
                    var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
                    if (d.getElementById(id)) {return;}
                    js = d.createElement('script'); js.id = id; js.async = true;
                    js.src = "//connect.facebook.net/fr_FR/all.js";
                    ref.parentNode.insertBefore(js, ref);
                }(document));
            </script>
            <a class="rs-link rs-fb no_target" href="javascript:void(0);" onclick="postToFeed();"><span class="fa fa-facebook"></span></a>
        </li>

        <li>
        	<a class="rs-link rs-twitter" target="_blank" href="http://twitter.com/intent/tweet?text=<?php echo addslashes($titre_share).' : '.addslashes($content_share); ?>&url=<?php echo addslashes($link_share); ?>"><span class="fa fa-twitter"></span></a>
        </li>
        <li>
        	<a class="rs-link rs-gplus" target="_blank" href="https://plus.google.com/share?url=<?php echo addslashes($link_share); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><span class="fa fa-google-plus"></span></a>
        </li>
        <li>
		    <a class="rs-link rs-pint pinterest" target="_blank" href="http://pinterest.com/pin/create/button/?url=<?php echo addslashes($link_share); ?>&media<?php echo addslashes($image_share); ?>&description=<?php echo addslashes($titre_share); ?>" class="pin-it-button" count-layout="horizontal"><span class="fa fa-pinterest"></span></a>
        </li>
        <!--li>
        	<a href="#" target="_blank" class="rs-link rs-insta"><span class="fa fa-instagram"></span></a>
        </li-->
        <li>
        	<a class="rs-link rs-linkedin" target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo addslashes($link_share); ?>&source=delacouraujardin"><span class="fa fa-linkedin"></span></a>
        </li>
    </ul>
</div>