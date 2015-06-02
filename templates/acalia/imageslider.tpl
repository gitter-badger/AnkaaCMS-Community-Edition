<div class="featured_slider">
        <!-- begin: sliding featured banner -->
         <div id="featured_border" class="jcarousel-container">
            <div id="featured_wrapper" class="jcarousel-clip">
               <ul id="featured_images" class="jcarousel-list">
               {foreach $header_imageSlider.slider as $image}
                  <li><img src="{$image.img}" width="{$image.width}" height="{$image.height}" alt="{$image.alt}" title="{$image.title}" /></li>
               {/foreach}
               </ul>
            </div>
            <div id="featured_positioner_desc" class="jcarousel-container">
               <div id="featured_wrapper_desc" class="jcarousel-clip">
                  <ul id="featured_desc" class="jcarousel-list">   
                  {foreach $header_imageSlider.slider as $image}
                     <li>
                        <div>
                           <p>
                              {$image.text}
                           </p>
                        </div>
                     </li> 
                  {/foreach}              
                  </ul>
               </div>

            </div>
            <ul id="featured_buttons" class="clear_fix">
               {foreach $header_imageSlider.slider as $key=>$image}
                  <li>{$key + 1}</li>
               {/foreach}
            </ul>
         </div>
         <!-- end: sliding featured banner -->
</div>