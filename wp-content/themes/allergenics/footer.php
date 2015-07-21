			<?php $title = get_field( 'title','option' ); ?>
            <?php $phone = get_field( 'phone','option' ); ?>
            <?php $email = get_field( 'email','option' ); ?>
            <?php $address = get_field( 'address','option' ); ?>
            <?php if ( $title || $phone || $email || $address || has_nav_menu( 'footer_nav' ) || have_rows( 'social_links','option' ) )  : ?>
                <footer id="footer">
                    <div class="container">
                        <?php ///////// Contact Section /////////// ?>
                        <?php if ( $title || $phone || $email || $address )  : ?>
                            <div class="contact">
                                <address>
                                    <?php if ( $title ) echo '<strong class="title">'. $title .'</strong>'; ?>
                                    <?php if ( $phone || $email )  : ?>
                                        <dl>
                                            <?php if ( $phone )  : ?>
                                                <dt><?php _e( 'Phone:', 'allergenics' ); ?></dt>
                                                <dd><a href="tel:<?php echo preg_replace('/\D/', '', $phone); ?>"><?php echo $phone; ?></a></dd>
                                            <?php endif; ?>
                                            <?php if ( $email )  : ?>
                                                <?php $email = antispambot( $email ); ?>
                                                <dt><?php _e( 'Email:', 'allergenics' ); ?></dt>
                                                <dd><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></dd>
                                            <?php endif; ?>
                                        </dl>
                                    <?php endif; ?>
                                    <?php if ( $address ) echo '<span>'. $address .'</span>'; ?>
                                </address>
                            </div>
                        <?php endif; ?>
                        <?php ///////// Menu Section /////////// ?>
                        <?php if( has_nav_menu( 'footer_nav' ) )
                            wp_nav_menu( array(
                                'container' => 'nav','container_class' => 'add-nav',
                                'theme_location' => 'footer_nav',
                                'link_after'      => '<span class="icon-btn-right"></span>',
                                'items_wrap'     => '<ul>%3$s</ul>'
                                )); ?>
                        <?php ///////// Social Links Section /////////// ?>
                        <?php if ( have_rows( 'social_links','option' )) : ?>
                            <div class="connect">
                                <h3><?php _e( 'CONNECT', 'allergenics' ); ?></h3>
                                <ul class="social-network">
                                    <?php while ( have_rows( 'social_links','option' )) : the_row(); ?>
                                        <li><a target="_blank" href="<?php echo esc_url(get_sub_field( 'link' )); ?>"><span class="<?php echo get_row_layout(); ?>"></span></a></li>
                                    <?php endwhile; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </footer>
            <?php endif; ?>
		</main>
	</div>
	<?php wp_footer(); ?>
</body>
</html>