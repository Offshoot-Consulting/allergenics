<?php get_header(); ?>
	<div class="container">
        <div id="content">
            <?php while ( have_posts() ) : the_post(); ?>
            
            <?php
            $position = get_post_meta( get_the_ID(), 'position', true );
            $education = get_post_meta( get_the_ID(), 'education', true );
            ?>
                
                <div <?php post_class(); ?> id="post-<?php the_ID(); ?>">

                	<div class="content">
                		<div class="teambox clearfix">
                		
                		<div class="team-left">   
                		
                		<?php the_title( '<h1>', '</h1>' ); ?>
                		<?php echo '<h2>' . $position . '</h2>'; ?>
                		<?php echo '<h3>' . $education . '</h3>'; ?>
                		
                    <?php the_content(); ?>
                    </div>
                    
                    <div class="team-right">
                    <?php the_post_thumbnail( 'full' ); ?>
                    </div>
                		
                		</div>
                		
                	</div>
                	<?php wp_link_pages(); ?>
                </div>
                
                <div style="clear:both"></div>
                
                <?php get_template_part( 'blocks/pager-single', get_post_type() ); ?>
            <?php endwhile; ?>
        </div>
        <?php get_sidebar(); ?>
        
                
        
        
    </div>
    
    
<section class="services-block">
                <div class="container">
                  
                  <h2>Our Testing Services</h2>                  
                  
                  <ul class="services-list">
                    <li class="" onclick="location.href='http://allergenicstesting.com/testing-services/food-and-environmental-sensitivity-assessment/'">
                      <div class="holder">
                       <div class="front">
                        <div class="bg-stretch">
                          <img alt="img-02" class="attachment-thumbnail_246x246" src="/wp-content/uploads/2015/03/img-02.jpg" style="width: 246px; height: 246px; margin-top: 0px; margin-left: 0px;">
                        </div>
                        <div class="text-area">
                          <div class="ico"><img alt="ico-01" class="attachment-thumbnail_97x75" src="/wp-content/uploads/2015/03/ico-01.png"></div>
                            <strong class="title">Food and Environmental Sensitivity Assessment</strong>
                          </div>
                        </div>
                        <div class="back">
                          <div class="text-box">
                            <p>This test is able to identify&nbsp;sensitivity to a broad range of different&nbsp;substances including food&nbsp;and environmental compounds.</p>
                          </div>
                        </div>
                        </div>
                      </li>
                      
                      <li class="" onclick="location.href='http://allergenicstesting.com/testing-services/organ-stress-assesment/'">
                        <div class="holder">
                          <div class="front">
                            <div class="bg-stretch"><img alt="img-03" class="attachment-thumbnail_246x246" src="/wp-content/uploads/2015/03/img-03.jpg" style="width: 246px; height: 246px; margin-top: 0px; margin-left: 0px;">
                            </div>
                          <div class="text-area">
                            <div class="ico"><img alt="ico-02" class="attachment-thumbnail_97x75" src="/wp-content/uploads/2015/03/ico-02.png"></div>
                            <strong class="title">Organ Stress <br>Assessment</strong>
                          </div>
                        </div>
                        <div class="back">
                          <div class="text-box">
                            <p>This test provides&nbsp;information on the&nbsp;health of the&nbsp;major organs of the body and whether or&nbsp;not organ stress is present.</p>
                          </div>
                        </div>
                      </div>
                    </li>
                    
                    <li class="" onclick="location.href='http://allergenicstesting.com/testing-services/vitamin-and-mineral-assessment/'">
                      <div class="holder">
                        <div class="front">
                          <div class="bg-stretch"><img alt="img-04" class="attachment-thumbnail_246x246" src="/wp-content/uploads/2015/03/img-04.jpg" style="width: 246px; height: 246px; margin-top: 0px; margin-left: 0px;"></div>
                          <div class="text-area">
                            <div class="ico"><img alt="ico-03" class="attachment-thumbnail_97x75" src="/wp-content/uploads/2015/03/ico-03.png"></div>
                            <strong class="title">Vitamin and Mineral Assessment</strong>
                          </div>
                          </div>
                          <div class="back">
                            <div class="text-box">
                              <p>This test provides information on the presence of&nbsp;a full&nbsp;range of vitamins, essential&nbsp;minerals and essential fatty&nbsp;acids.</p>
                              </div>
                            </div>
                          </div>
                        </li>
                        
                        <li class="" onclick="location.href='http://allergenicstesting.com/testing-services/heavy-metal-and-toxic-element-assessment/'">
                          <div class="holder">
                            <div class="front">
                              <div class="bg-stretch"><img alt="img-05" class="attachment-thumbnail_246x246" src="/wp-content/uploads/2015/03/img-05.jpg" style="width: 246px; height: 246px; margin-top: 0px; margin-left: 0px;"></div>
                              <div class="text-area">
                                <div class="ico"><img alt="ico-04" class="attachment-thumbnail_97x75" src="/wp-content/uploads/2015/03/ico-04.png"></div>
                                <strong class="title">Heavy Metal and Toxic Element Assessment</strong>
                              </div>
                            </div>
                            <div class="back">
                              <div class="text-box">
                                <p>This test provides qualitative information on the presence of heavy metals and toxic elements in the body.</p>
                              </div>
                            </div>
                          </div>
                        </li>
                      </ul>
                      
                      <a class="btn" href="/order-your-test/">ORDER YOUR TEST NOW</a>
                      <a class="more" href="/?page_id=7">Learn More</a>
                      
                    </div>
            </section>
    
<?php get_footer(); ?>