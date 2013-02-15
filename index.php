<?php 
if (isset($_POST['submit']) && isset($_GET['idea']) && $_GET['idea'] == 'submitted') {
    $link = $_POST['link'];
    $type = $_POST['type'];
    $ID = wp_insert_post(array(
        'post_title' => 'New idea submitted on '.date('F j'),
        )
    );
    update_post_meta($ID, 'type', $_POST['type']);
    update_post_meta($ID, 'link', $link);

    $submit = 'submitted';

    if (get_option('idea_email') == true) {
        $emailTo = 'george@abide-idea.com,scott.p.donaldson@gmail.com';
        $subject = 'New idea submitted on '.date('F j');
        $body = 'Link: '.$link."\n\n".
                'Type of idea: '.$type."\n\n".
                "\n\n".
                'Approve or trash this idea: http://www.abide-idea.com/wp-admin';

        $headers = 'From: admin@abide-idea.com <admin@abide-idea.com>' . "\r\n" . 'Reply-To: admin@abide-idea.com';
        
        mail($emailTo, $subject, $body, $headers);
    }
} 
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js modern"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    
    <title><?php wp_title(''); ?></title>

    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width" />
    
    <link rel="author" href="<?php echo bloginfo('template_url'); ?>/humans.txt">

    <link rel="icon" href="<?php echo bloginfo('template_url'); ?>/favicon.ico">

    <link rel="stylesheet" href="<?php echo bloginfo('template_url'); ?>/style.css">
    <link rel="stylesheet" href="<?php echo bloginfo('template_url'); ?>/css/style.css">
    <script src="<?php echo bloginfo('template_url'); ?>/js/vendor/modernizr.js"></script>
<?php wp_head(); ?>
</head>

<?php if (isset($submit)) { ?>
<body <?php body_class($submit); ?> >
<?php } else { ?>
<body <?php body_class(); ?> >
<?php } ?>

<div id="page" class="hfeed site">
    
    <?php /* As of Jan. 2013, we are still supporting IE7. Sigh... */ ?>

	<!--[if lt IE 7]>
        <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
    <![endif]-->

<header class="full-width clearfix">
    <div class="full-content clearfix">
        <h1 id="site-title" class="visuallyhidden"><?php bloginfo('name'); ?></h1>
        <a class="logo" href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>" rel="home">
            <img src="<?php echo bloginfo('template_url'); ?>/images/logo.png" />
        </a>

        <nav role="navigation">
        	<?php wp_nav_menu( array(
                'theme_location' => 'primary',
                'container' => false,
                'fallback_cb' => 'wp_page_menu',
            ) ); ?>   
        </nav>
    </div>
    <div class="bar"></div>
</header>

<section id="masthead" class="full-width" role="masthead">
    <div class="full-content">
        <div class="content">
            <h2>A trusted guide on your journey to develop&nbsp;new&nbsp;ideas.</h2>
            <div class="compass_container">
                <div id="compass">
                    <img class="compass" src="<?php echo bloginfo('template_url'); ?>/images/compass.png" />
                    <img class="needle" data-rotate="" src="<?php echo bloginfo('template_url'); ?>/images/needle.png" />
                    <img class="circle" src="<?php echo bloginfo('template_url'); ?>/images/circle.png" />
                </div>    
            </div>
            <h3>A friend to ideas and the people who&nbsp;have&nbsp;them.</h3>
        </div>
    </div>
</section>

<section id="specialties" class="full-width module clearfix">
    <div class="full-content">
        <h2><span>Specialties</span></h2>
        <div class="left">
            <ul>
                <li class="odd">Idea <br>Generation
                    <span class="icon-plus"></span>
                </li>
                <li class="even">Team <br>Building
                    <span class="icon-plus"></span>
                </li>
                <li class="odd">Qualitative <br>Research
                    <span class="icon-plus"></span>
                </li>
                <li class="even">Strategic <br>Development
                    <span class="icon-plus"></span>
                </li>
            </ul>
        </div>
        <div class="right">
            <div class="content">
                <?php the_field('specialties', 'options'); ?>
            </div>
            <div class="specialties">
                <div class="specialty infobox">
                    <p><?php the_field('idea_generation', 'options'); ?></p>
                </div>
                <div class="specialty infobox">
                    <p><?php the_field('team_building', 'options'); ?></p>
                </div>
                <div class="specialty infobox">
                    <p><?php the_field('qualitative_research', 'options'); ?></p>
                </div>
                <div class="specialty infobox">
                    <p><?php the_field('strategic_development', 'options'); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="clients" class="full-width module clearfix">
    <div class="full-content">
        <h2><span>Clients</span></h2>
        <div class="right">
            <ul>
                <?php 
                while (has_sub_field('quotes', 'options')) { ?>
                
                <li><?php the_sub_field('brief'); ?>
                    <span class="icon-plus"></span></li>

                <?php } ?>
            </ul>
        </div>
        <div class="left">
            <div class="content">
                <?php the_field('quotes_text', 'options'); ?>
            </div>
            <div class="quotes">
                <?php 
                while (has_sub_field('quotes', 'options')) { ?>
                    <div class="quote infobox">
                        <p><?php the_sub_field('full'); ?></p>
                    </div>
                <?php } ?>
            </div>    
        </div>
    </div>
</section>

<section id="about" class="full-width module clearfix">
    <div class="full-content">
        <h2><span>About</span></h2>
        <div class="left">
            <img src="<?php echo bloginfo('template_url'); ?>/images/headshot.jpg" />
        </div>
        <div class="right">
            <div class="content">
                <?php the_field('about', 'options'); ?>
            </div>
        </div>
    </div>
</section>

<section id="connect" class="full-width module clearfix">
    <div class="full-content clearfix">
        <h2><span>Connect</span></h2>
        <div class="left">
            <h3>Ready to start an exploration?</h3>
            <p>Ph. <?php the_field('phone', 'options'); ?><br />
               E. <a href="mailto:<?php the_field('email', 'options'); ?>"><?php the_field('email', 'options'); ?></a><p>
            <p><a href="https://www.facebook.com/abideidea" target="_blank">Facebook</a><br />
               <a href="https://twitter.com/abideidea" target="_blank">Twitter</a></p>
        </div>
        <div class="right">
            <div class="content">
                <h3>Begin the journey with some interesting ideas from around the web:</h3>

                <?php 
                while (have_posts()) : the_post(); 
                    $class = 'icon-'.get_field('type');
                    $link = get_field('link'); ?>

                    <a href="<?php echo $link; ?>" target="_blank">
                        <span class="<?php echo $class; ?>"></span>&nbsp;<?php the_title(); ?>
                    </a>

                <?php endwhile; ?>

                <form id="idea" class="clearfix" method="POST" action="<?php echo home_url(); ?>/?idea=submitted#idea">
                    <input type="url" value="http://" id="link" name="link">
                    <div class="types">
                        <span class="icon-image" data-type="image"></span>
                        <span class="icon-video" data-type="video"></span>
                        <span class="icon-article" data-type="article"></span>
                    </div>
                    <input type="hidden" name="type" id="type">
                    <input type="submit" name="submit" id="submit" value="Submit an Idea">

                    <div class="thanks infobox">
                        <p>Thanks for the inspiration!</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<footer class="full-width clearfix">
    <div class="full-content">
        <div class="left">
        	<p class="alignright">&copy; <?php echo date('Y'); ?> Abide Idea Company</p>
        </div>
        <div class="right">
        	<p class="alignleft">design + code by <a href="http://www.parsleyandsprouts.com" target="_blank">Parsley &amp; Sprouts</a></p>
        </div>
    </div>
</footer> 

</div><!-- #page -->

<script src="<?php echo bloginfo('template_url'); ?>/js/plugins.js"></script>
<script src="<?php echo bloginfo('template_url'); ?>/js/script.js"></script>

<?php wp_footer(); ?>
</body>
</html>