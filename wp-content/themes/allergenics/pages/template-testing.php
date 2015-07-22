<?php

/*

Template Name: Template Testing

*/

get_header(); ?>







        <?php while ( have_posts()) : the_post(); ?>

                <section class="form-section innerpage">

                  <div class="container clearfix">

                    <div class="cont-left" style="width:100%">

                    <h1><?php the_title(); ?></h1>

                    <?php the_content(); ?> 

                    </div>
                 
                  </div>
                  
               </section>   

                        <section class="carousel-block">
                        
                        <h2>What our customers are saying</h2>
                        
                          <div class="container">
                                  <div class="carousel">
                                      <div class="mask">
                                          <div class="slideset">
                                              <div class="slide">
                                                <blockquote>
                                                    <q>I am so delighted with the results that I have recommended your services to several of my friends and colleagues. I made all the recommended dietary changes and with help from my Naturopath, I now cannot believe what a difference this has made to my life.</q>                                                    <cite>&ndash; I. E - New Zealand</cite>                                                </blockquote>
                                              </div>
                                              <div class="slide">
                                                <blockquote>
                                                    <q>The daily eating guide has been very helpful to know what I can eat, and I feel better because of it!  I am so glad I found Allergenics!</q>                                                    <cite>&ndash; E - Auckland</cite>                                                </blockquote>
                                            </div>
                                            <div class="slide">
                                                <blockquote>
                                                    <q>Wish I hadn't waited and wish I had found this place earlier. I have been totally amazed by the affect cutting these foods out of my diet has had. My energy levels skyrocketed after 1 WEEK (Noticed by my Husband too.)</q>                                                    <cite>&ndash; N W - New Zealand</cite>                                                </blockquote>
                                            </div>
                                            <div class="slide">
                                                <blockquote>
                                                    <q>The results were outstanding - I first noticed after about a week how much more energy that I had - I would literally bounce out of bed in the morning. Thanks for giving me my spring in my step back - I would definitely recommend a hair test to all!!!</q>                                                    <cite>&ndash; A. Carr</cite>                                                </blockquote>
                                            </div>
                                            <div class="slide">
                                                <blockquote>
                                                    <q>I just wanted to write and say how grateful we are of your services and especially the on-line option . Gilbert's skin is now amazingly clear and he is a much more contented and happy boy who is now able to reach 
his full potential. </q>                                                    <cite>&ndash; TM Mother</cite>                                                </blockquote>
                                            </div>
                                            <div class="slide">
                                                <blockquote>
                                                    <q>One of my most valuable assets in my naturopathic work would definitely be having the services available from Allergenics. 
I have found their intolerance testing to be incredibly accurate.</q>                                                    <cite>&ndash; Lorna McInnes ND</cite>                                                </blockquote>
                                            </div>
                                            <div class="slide">
                                                <blockquote>
                                                    <q>For me, working with Allergenics has provided us with insights, information and support that I am truly grateful for.  These results have had a direct impact to our health and well being and we will be forever grateful!</q>                                                    <cite>&ndash; K.A.</cite>                                                </blockquote>
                                            </div>
                                          </div>
                                      </div>
                                      <a class="btn-prev" href="#"><span class="icon-btn-left"></span></a>
                                      <a class="btn-next" href="#"><span class="icon-btn-right"></span></a>
                                  </div>
                          </div>
                      </section>


        <?php endwhile; ?>

               

<?php get_footer(); ?>