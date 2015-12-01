<?php
/*
Template Name: Template About Us
*/
get_header(); ?>

<?php $learn_more_link = get_field( 'learn_more_link' , 'option' ); ?>
<?php $order_test_link = get_field( 'order_test_link' , 'option' ); ?>

        <?php while ( have_posts()) : the_post(); ?>
                <section class="form-section innerpage">
                  <div class="container clearfix">
                    <div class="cont-left" style="width:100%">
                    <h1><?php the_title(); ?></h1>
                    <?php the_content(); ?> 
                    
                    <div class="team">
                        <?php 

                              $args = array('post_type' => 'team', 'order' => 'ASC');
                          
                               $loop = new WP_Query($args);
                               
                               echo '<ul>';
                               if($loop->have_posts()) {

                                  while($loop->have_posts()) : $loop->the_post();
                                  
                                  $position = get_post_meta( get_the_ID(), 'position', true );
                                  $education = get_post_meta( get_the_ID(), 'education', true );
                                  $shortdesc = get_post_meta( get_the_ID(), 'shortdesc', true );
                                  $permalink = get_the_permalink();
                                  $teamname = get_the_title();
                                  
                                      echo '<li><a href="' . $permalink . '">';
                                      the_post_thumbnail('ddc');
                                      echo '</a><a style="text-decoration:none" href="' . $permalink . '"><h3>' . $teamname . '</h3></a>';
                                      echo '<span class="position">' . $position . '</span>';
                                      echo '<span class="education">' . $education . '</span>';
                                      echo '<span class="shortdesc">' . $shortdesc . '</span>';
                                      echo '<a href="' . $permalink . '">View Profile</a>';
                                      echo '</li>';
                                  endwhile;
                               }
                               echo '</ul>';

                          ?>
                    </div>
                    
                    </div>
                    <div class="cont-right">
                      <?php //dynamic_sidebar( 'default-sidebar' ); ?>
                    </div>
                  </div>
                </section>
        <?php endwhile; ?>
        
        
        
        <section class="services-block">
                <div class="container">
                  
                  <h2>Our Testing Services</h2>                  
                  
                  <ul class="services-list">
                    <li class="">
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
                      
                      <li class="">
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
                    
                    <li class="">
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
                        
                        <li class="">
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
                      
                    <a href="/hair-testing-services/" class="btn"><?php _e( 'SEE OUR HAIR TESTS', 'allergenics' ); ?></a>
                      
                </div>
            </section>
        
        
<?php get_footer(); ?>