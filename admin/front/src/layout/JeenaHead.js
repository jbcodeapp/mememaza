import Head from "next/head";

import {HOME_URL} from '../../def';

const JeenaHead = () => {
  return (
    <Head>
      {/* Required meta tags */}
      <meta charSet="utf-8" />
      <meta name="description" content="" />
      <meta httpEquiv="X-UA-Compatible" content="IE=edge" />
      <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no"
      />
      {/* Title */}
      <title>
      Memesmaza
      </title>
      {/* Favicon Icon */}
      <link
        rel="shortcut icon"
        href="assets/images/favicon.png"
        type="image/x-icon"
      />
      {/* Google Fonts */}
      <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&family=Roboto:wght@400;500;700&display=swap"
        rel="stylesheet"
      />
      {/* Flaticon */}
      <link rel="stylesheet" href={HOME_URL+'assets/css/flaticon.min.css'} />
      {/* Font Awesome */}
      <link rel="stylesheet" href={HOME_URL+'assets/css/fontawesome-5.14.0.min.css'} />
      {/* Bootstrap */}
      <link rel="stylesheet" href={HOME_URL+'assets/css/bootstrap.min.css'} />
      {/* Magnific Popup */}
      <link rel="stylesheet" href={HOME_URL+'assets/css/magnific-popup.min.css'} />
      {/* Nice Select */}
      <link rel="stylesheet" href={HOME_URL+'assets/css/nice-select.min.css'} />
      {/* Animate */}
      <link rel="stylesheet" href={HOME_URL+'assets/css/animate.min.css'} />
      {/* Slick */}
      <link rel="stylesheet" href={HOME_URL+'assets/css/slick.min.css'} />
      {/* Main Style */}
      <link rel="stylesheet" href={HOME_URL+'assets/css/style.css'} />
	  
	  {/* 
		<link rel="stylesheet" href='http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css' />
	   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		*/}
    </Head>
  );
};
export default JeenaHead;
