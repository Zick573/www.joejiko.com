@extends('layouts.master')
@section('page.title')
  Web developer résumé &amp; skills
@stop

@section('content')
  <article id="page-resume" class="base-article resume">
    <header class="grid-12">
      <h1>Résumé</h1>
      <h2>Need something done right?</h2>
<!--       <nav class="resume-nav">>
        <a href="/resume/objective">objective</a>
        <a href="/resume/proficiency">proficiency</a>
        <a href="/resume/experience">experience</a>
        <a href="/resume/portfolio">portfolio</a>
        <a href="/resume/strengths">strengths</a>
        <a href="/resume/references">references</a>
      </nav> -->
    </header>
    <article class="grid-8">
      <section id="objective">
        <h2>Objective</h2>
        <article>
          <p>To pursue a career in one or more of, but not limited to, the following fields of web application development, mobile applications, graphic design, computer science, artificial intelligence, voice recognition, illustration, information systems, space travel, warp field technology, or information technology (I like to do a lot of things!).</p>
          <p><strong>TL;DR to pursue a career in making cool shit</strong></p>
        </article>
      </section>

      <section id="expert">
        <h2>Expert at</h2>
        <h3>Latest versions of</h3>
        <article>
          <p>PHP</p>
          <p>
            <a href="https://developer.mozilla.org/en-US/docs/Web/JavaScript" target="_blank">Javascript</a>,
            <a href="http://jquery.com/" target="_blank">jQuery</a>
          </p>
          <p>
            <a href="http://www.w3schools.com/html/html5_intro.asp">HTML</a>
          </p>
          <p>
            <a href="http://www.w3schools.com/css3/">CSS</a>
          </p>
        </article>
      </section>

      <section id="proficiency">
        <h2>Proficient In</h2>
        <article>
          <p>Microsoft Windows XP, Vista, 7, 8, Mac OS, iOS, Android, Linux (ArchLinux, Ubuntu)</p>
          <p>Adobe Creative Suite: Photoshop, Illustrator (graphic design), (web design and web development), InDesign (layout), <a href="http://www.adobe.com/products/dreamweaver.html">Dreamweaver</a></p>
          <p>Sublime Text 2, <a href="http://netbeans.org/">Netbeans</a></p>
          <p>Microsoft Office: Word, Excel, PowerPoint, Publisher, Outlook. Google Drive, Dropbox</p>
          <p>Apache</p>
          <p><a href="http://oauth.net/">oAUTH</a></p>
          <p>Facebook Graph API, Google APIs, Google analytics, Twitter API, etc.</li>
          <p>Smarty (templating engine)</p>
          <p><strong>TL;DR all major and popular software used to produce for the web</strong></p>
        </article>
        <h2>Other libraries and systems I've spent time with</h2>
        <article>
          <ul>
            <li>ModX, Laravel 4</li>
            <li>Prestashop</li>
            <li>NotORM, xPDO</li>
            <li>Slim (PHP framework)</li>
            <li>CKEditor, Dojo</li>
          </ul>
        </article>
      </section>

      <section id="experience">
        <h2>Experience</h2>
        <article>
          <h3>Web applications, internal development <a href="http://www.postcardmania.com">PostcardMania</a></h3>
          <p>May 2013&mdash;<em>Current</em></p>
          <ul>
            <li>Super secret code stuff ;]</li>
          </ul>
        </article>
        <article>
          <h3>Client web development, design at <a href="http://www.postcardmania.com">PostcardMania</a></h3>
          <p>June 2012&mdash;<em>May 2013</em></p>
          <ul>
            <li>Set up reseller hosting account with Hostgator. Configured WHM packages and integrated with WHMCS</li>
            <li>Custom ecommerce layout design and functionality</li>
            <li>PSD to HTML5</li>
            <li>Mobile website design and development</li>
            <li>Web applications</li>
            <li>Custom front-end project management application (using JavaScript and jQuery)</li>
            <li>Front-end interface and experience design</li>
            <li>Designed, developed, and implemented a database driven cross-browser form submission system</li>
            <li>Developed a form and lead tracking system for use on landing pages</li>
            <li>Developed a dynamic form generating widget for anywhere. Pulls information from a local database and dynamically loads content.</li>
          </ul>
        </article>

        <article>
          <h3>Graphic/Web Designer, Developer, and Prepress at <a href="http://www.willywalt.com">Willy Walt</a></h3>
          <p>June 2011&mdash;June 2012</p>
          <ul>
            <li>Designed an advanced 1-step checkout and 1-step product upgrade system with a custom date calendar to calculate shipping and rush production costs. Still in use here and has processed thousands of orders without fail.</li>
            <li>Coded a custom ecommerce (quick checkout) system with AJAX input validation and integrated it with their existing accounts, ticket system, ordering system, and payment gateway using PHP and jQuery.</li>
            <li>Designed and coded a custom PDF proof viewer using jQuery (JavaScript) and HTML5</li>
            <li>Designed mockups and coded HTML/CSS for mutliple new landing pages and client sites.</li>
            <li>Redesigned landing pages and revised code to adhere to current web standards and work in all modern browsers (cross browser compatibility).</li>
            <li>Performed prepress set up for customer artwork to ensure quality printing production (image quality/resolution checks, safe areas/bleeds, color mode, etc.).</li>
            <li>Designed custom graphics for multiple printed products including business cards, postcards, brochures, pocket folders, and door hangers.</li>
          </ul>
        </article>

        <article>
          <h3>Retail technology sales, PC Troubleshooting, installation, and Repairs at <a href="http://staples.com" target="_blank">Staples</a></h3>
          <p>March 2008&mdash;June 2012</p>
          <ul>
            <li>Troubleshooted customer PCs (running Windows) and solved common issues such as PCs not connecting to the Internet, failing to boot up, or running slowly.</li>
            <li>Installed or Upgraded all types of PC hardware (excluding motherboard), peripherals (such as printers, webcams, and network cards), and major software titles.</li>
            <li>Qualified customers to retail technology products and services based on needs and budget.</li>
          </ul>
        </article>

        <article>
          <h3>Freelance, Full Stack Web Developer, Graphic Designer, and IT Expert at Creative Spine &amp; <a href="/">JoeJiko.com</a> (JJCOM)</h3>
          <p>Since August 2006</p>
          <ul>
            <li>Designed unique logos.</li>
            <li>Designed unique, custom graphics for web and print.</li>
            <li>Designed custom facebook page covers.</li>
            <li>Designed custom layouts for Myspace and Stickam profiles.</li>
            <li>Designed and developed websites in multiple industries from scratch to adhere to customer specifications.</li>
            <li>Designed and developed an <a href="/apps/love-calculator">online game</a></li>
            <li>Lots of art and digital illustrations</li>
            <li>Solved technology problems, set up computer networks, configured mobile devices, PCs, printers, and copiers.</li>
          </ul>
        </article>
      </section>

      <section id="portfolio">
        <h2>Portfolio</h2>
        <article>
          <p>Browse around a bit. View the source. This whole site is my portfolio, enjoy!</p>
        </article>
      </section>

      <section id="strengths">
        <h2>Strengths</h2>
        <article>
          <ul>
            <li>Creating unique, visually appealing websites and web applications using the latest web technologies such as HTML5, CSS3, Javascript, &amp; jQuery (a JavaScript library) {note: I don't *just* use jQuery plugins and I understand regular javascript too.}</li>
            <li>User interfaces and positive user experience.</li>
            <li>Website development, redesign, and maintenance.</li>
            <li>Responsive web design (designing for multiple platforms &amp; screen sizes).</li>
            <li>CMS theming &amp; customization: <a href="http://wordpress.org/">WordPress</a>, <a href="http://modx.com/">MODX</a>, <a href="http://www.joomla.org/">Joomla!</a>, <a href="http://prestashop.com">PrestaShop</a> (ecommerce), <a href="http://www.cs-cart.com/">CS-Cart</a> (ecommerce), <a href="http://www.shopify.com/">Shopify</a> (ecommerce), Etc.</li>
            <li>Web hosting &amp; server configuration</li>
            <li>Database planning</li>
            <li>Cross-browser compatibility (except anything older than IE9 because why bother?)</li>
            <li>PC troubleshooting, installations, and repair. Network installation &amp; configuration.</li>
            <li>Problem solving.</li>
            <li>There's more.. just <a href="/contact/questions">ask me</a>.</li>
          </ul>
        </article>
      </section>

      <section id="references">
        <h2>References</h2>
        <article>
          <p>Available upon request</p>
        </article>
      </section>
    </article>
    <aside class="grid-4">
      <p>
        <span>Ready to talk?</span> <a class="btn green" href="/contact">Contact me now</a>
      </p>
    </aside>
  </article>
@stop