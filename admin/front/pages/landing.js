import Layout from "@/layout";
import { useRouter } from "next/router";
import React, {useState, useEffect, useCallback} from 'react';
import { testimonialSlider } from "@/src/sliderProps";
import dynamic from "next/dynamic";
import Link from "next/link";
import { Nav, Tab } from "react-bootstrap";
import Slider from "react-slick";
import { HOME_URL, SITE_URL, localData } from '../def';
import axios from 'axios';

import { ReactSearchAutocomplete } from 'react-search-autocomplete'
//import { saveAs } from "file-saver";


/* 
import FileSaver from 'file-saver';
import "react-responsive-carousel/lib/styles/carousel.min.css"; // requires a loader
import { Carousel } from "react-responsive-carousel";
import { items } from "./Items.json";
import styles from "./Responsive.module.css"; */

//import Carousel from "react-elastic-carousel";
import styles from "./Elastic.module.css";
import Carousel from "@itseasy21/react-elastic-carousel";

import Fancybox from './fancymodulecp.jsx';

import {
  Button,
  Card,
  CardHeader,
  CardBody,
  FormGroup,
  Form,
  Input,
  InputGroupAddon,
  InputGroupText,
  InputGroup,
  Modal,
  Row,
  Col,
} from "react-bootstrap";

const Landing = (props) => {
	let token = localData.load('token');
	const [isLogin, setIsLogin] = useState(false);
	const loc = useRouter();
	let data = props.props.data;
	let reelslist = data.reels;
	
	const [count, setCount] = useState(0);
	const [page, setPage] = useState(1);
	const [pcount, setPcount] = useState(data.count);
	const [categories, setCategories] = useState(data.categories);
	const [reels, setReels] = useState(data.reels);
	const [post, setPost] = useState(data.post);
	const [loading, setLoading] = useState(true);
	const [show, setShow] = useState(false);
	const [commentModalshow, setCommentModalshow] = useState(false);
	const [search, setSearch] = useState([]);

	const [modaltitle, setModalTitle] = useState('Login');
	const [loginForm, setModalLoginForm] = useState('');
	const [registerForm, setModalRegisterForm] = useState('d-none');
	const [remail, setRemail] = useState('');
	const [rname, setRname] = useState('');
	const [rpassword, setRpassword] = useState('');
	const [lemail, setLemail] = useState('');
	const [lpassword, setLpassword] = useState('');
	const [registerDisabled, setRegisterDisabled] = useState('');
	
	const [comment, setComment] = useState('');
	const [commentDisabled, setcommentDisabled] = useState('');
	
	const [loginDisabled, setLoginDisabled] = useState('');
	const [regErrors, setRegErrors] = useState({});
	const [registerErr, setRegisterErr] = useState([]);
	const [loginErr, setLoginErr] = useState([]);
	const [download, setDownload] = useState(false);
	
	const [postDetail, setPostDetail] = useState({});
	const [postCountStatus, setPostCountStatus] = useState({'view': 0, 'like': 0, 'share': 0, 'comment': 0});
	const [postDetailComment, setPostDetailComment] = useState([]);
	const [loadComment, setPostLoadComment] = useState(0);
	
	// Used to toggle Modal on and off
	const [isOpen, setIsOpen] = useState(false);

	const handleClose = () => {
        setShow(false);
		setCommentModalshow(false);
		setIsOpen(false);
    }
	
	const handleInfiniteScroll = async (e) => {
		if (loading === true){
			try {
				if(window.innerHeight + e.target.documentElement.scrollTop + 1 >= e.target.documentElement.scrollHeight) {
					setPage((prev) => prev + 1);
				}
			} catch (error) {
				//console.log(error);
			}
		}
	}
	
	
	useEffect(() => {
		if(token != null) {
			setIsLogin(true);
		}
		
	}, [token]);
	
	useEffect(() => {
		if (loading === true){
			
			window.addEventListener('scroll', handleInfiniteScroll);
		}
	}, []);
	
	const getPostData = async () => {
		//if (loading === true){ 
			if(pcount == 0) {
				return;
			}
			setLoading(false);
			let slug = loc.query.slug;
			
			let url = SITE_URL+`/post?page=${page}`;
			if(slug != undefined) {
				url = SITE_URL+`/post/${slug}?page=${page}`;
			}
			let headerAccess = localData.headerAccess();
			
			var config = {
				headers: headerAccess
			};
			
			await axios.get(url, config)
				.then((response) => {
					setLoading(true);
					setPcount(response.data.postcount);
					setPost((prev) => [...prev, ...response.data.post]);
					
			}, (error) => {
			  
			});
		//}
	}
	
	function upsert(array, element) { // (1)
	  const i = array.findIndex(_element => _element.id === element.id);
	  if (i > -1) array[i] = element; // (2)
	  else array.push(element);
	}
	
	async function logout() {
		if(isLogin === true) {
			let headerAccess = localData.headerAccess();
			var config = {
				headers: headerAccess
			};
			console.log(config);
			try {
			
				let res = await axios.post(SITE_URL+'/logout', {}, config);
				
				if(res.data.statuscode === true) {
					localStorage.clear();
					setIsLogin(false);
				}
				
			} catch (error) {
				
				if(error.response.status == 401) {
					localStorage.clear();
					setIsLogin(false);
					console.log(error.response.data.message);
				}
			}
		}
	}
	
	async function handleLike(id) {
		let headerAccess = localData.headerAccess();
		var config = {
			headers: headerAccess
		};
		
		try {
			
			let res = await axios.post(SITE_URL+'/hlike', {'type_id':id}, config);
			
			if(res.data.statuscode === true) {
				let val = res.data.like;
				if(val > 0) {
					setPost((prev) => {
						let objIndex = prev.findIndex((prev => prev.id == id));
						prev[objIndex].like = prev[objIndex].like = val;
						return prev.map((match) => {
							return match
						})
					})
				}
			}
			
		} catch (error) {
			if(error.response.status == 401) {
				console.log(error.response.data.message);
				localStorage.clear();
				setIsLogin(false);
			}
			
		}
	}
	
	const signin = () => {
		if(isLogin === false) {
			setModalTitle('Login');
			setModalRegisterForm('d-none');
			setModalLoginForm('');
			setShow(true);
		}
	}
	
	const signup = () => {
		if(isLogin === false) {
			setModalTitle('Register');
			setModalRegisterForm('');
			setModalLoginForm('d-none');
			setShow(true);
		}
	}
	
	const handleIncrement = (e, id) => {
		if(isLogin === false) {
			setModalTitle('Login');
			setModalRegisterForm('d-none');
			setShow(true);
		} else {
			handleLike(id);
		}
	}
	
	const handleView = async (e, id) => {
		e.preventDefault();
		setPostDetail({});
		setPostDetailComment([]);
		let headerAccess = localData.headerAccess();
		var config = {
			headers: headerAccess
		};
		let url = SITE_URL+`/detail/${id}`;
		try {
			await axios.get(url, config)
				.then((response) => {
					//setCommentModalshow(true);
						setIsOpen(true);
						setPostDetail(response.data.obj);
						setPostDetailComment(response.data.obj.comments);
						
						setPostLoadComment(response.data.nextpage);
						
						setPost((prev) => {
							let objIndex = prev.findIndex((prev => prev.id == id));
							prev[objIndex].view = prev[objIndex].view = response.data.obj.view;
							return prev.map((match) => {
								return match
							})
						})
/*
					FileSaver.saveAs(
						response.data.cdnimage,
						"fileNameYouWishCustomerToDownLoadAs.anyType");
				 */
	
			}, (error) => {
			  
			});
		} catch (err) {
			
		}
		
	}
	
	useEffect(() => {
		if(page > 1) {
			getPostData();
		} else {
			setPost(data.post);
		}
		
	}, [page, loc.asPath]);
	
	async function registerAction(body) {
		setRegisterDisabled('disabled');
		try {
			let statelist = await axios.post(SITE_URL+'/register', body);
			
			if(statelist.data.statuscode == true) {
				setRegisterDisabled('');
				alert(statelist.data.message);
				setModalLoginForm('');
				setModalTitle('Login');
				setModalRegisterForm('d-none');
				setRegisterErr([]);
				setLoginErr([]);
				
			} else if(statelist.data.statuscode == false) {
				setRegisterErr(statelist.data.errors);
				setRegisterDisabled('');
			}
		} catch (err) {
			if(err.response.status == 401) {
				
			}
			setRegisterDisabled('');
		}
		
	}
	
	function register(event) {
		
		const body = { name: rname, email: remail, password: rpassword };
		registerAction(body);
		event.preventDefault();
	}
	
	const loadcommentdata = (postid, pageid) => (e) => {
		
		loadCommentAction(postid, pageid);
		event.preventDefault();
	}
	
	async function loadCommentAction(postid, pageid) {
		
		try {

			const response =  await axios.get(SITE_URL+`/loadcomment/${postid}/${pageid}`);
			
			var storecomment = [...postDetailComment, ...response.data.comments];
			setPostDetailComment(storecomment);
			setPostLoadComment(response.data.nextpage);
			
		} catch (error) {
			
		}
		
	}
	
	const commentsave = (id) => (e) => {
		
		const body = { postid: id, comment: comment };
		commentAction(body);
		event.preventDefault();
	}
	
	async function commentAction(body) {
		let headerAccess = localData.headerAccess();
		var config = {
			headers: headerAccess
		};
		setcommentDisabled('disabled');
		try {

			let res = await axios.post(SITE_URL+'/comment', body, config);

			if(res.data.statuscode === true) {
				console.log(res.data.post.comments);
				setPostDetailComment(res.data.post.comments);
				setcommentDisabled('');
				setComment('');
				let post = res.data.post;
				if(post.comment > 0) {
					setPost((prev) => {
						let objIndex = prev.findIndex((prev => prev.id == post.id));
						prev[objIndex].comment = prev[objIndex].comment = post.comment;
						return prev.map((match) => {
							return match
						})
					})
					
					setPostCountStatus({'view': post.view, 'like': post.like, 'share': post.share, 'comment': post.comment})
				}
			} else if(res.data.statuscode === false) {
				setcommentDisabled('');
				alert(res.data.error);
				
			}
			
		} catch (error) {
			if(error.response.status == 401) {
				//console.log(error.response.data.message);
				localStorage.clear();
				setIsLogin(false);
			}
			
		}
		
	}
	
	async function loginAction(body) {
		setLoginDisabled('disabled');
		try {
			let statelist = await axios.post(SITE_URL+'/login', body);
			
			if(statelist.data.statuscode == true) {
				localStorage.setItem('token', statelist.data.token);
				localStorage.setItem('userdata', JSON.stringify(statelist.data.user));
				alert(statelist.data.message);
				setRegisterErr([]);
				setLoginErr([]);
				setLoginDisabled('');
				setLemail('');
				setShow(false);
				setIsLogin(true);
				setLpassword('');
				
			} else if(statelist.data.statuscode == false) {
				setLoginErr(statelist.data.errors);
				setLoginDisabled('');
			}
		} catch (err) {
			console.log(err);
			/* if(err.response.status == 401) {
				
			} */
			setLoginDisabled('');
		}
		
	}
	
	function login(event) {
		
		const body = { email: lemail, password: lpassword };
		
		loginAction(body);
		
		event.preventDefault();
	}
	
	function handleRegister() {
		setModalTitle('Register');
		setModalRegisterForm('');
		setModalLoginForm('d-none');
	}
	
	function handleLogin() {
		setModalTitle('Login');
		setModalRegisterForm('d-none');
		setModalLoginForm('');
	}
	
	
	/**/
	async function filtersearch(search) {
		console.log(search);
		try {
			let searchdata = await axios.get(SITE_URL+`/search/${search}`);
			/* let items = [
			{
			  id: 0,
			  name: 'Cobol'
			},
			{
			  id: 1,
			  name: 'JavaScript'
			},
			{
			  id: 2,
			  name: 'Basic'
			},
			{
			  id: 3,
			  name: 'PHP'
			},
			{
			  id: 4,
			  name: 'Java'
			}
		];
			console.log(items);
			setSearch(items); */
			console.log(searchdata.data);
			setSearch(searchdata.data);
		} catch (err) {
			
		}
		
	}
	
	  const handleOnSearch = (string, results) => {
		// onSearch will have as the first callback parameter
		// the string searched and for the second the results.
		
		filtersearch(string);
		
		/* let items = [
			{
			  id: 0,
			  name: 'Cobol'
			},
			{
			  id: 1,
			  name: 'JavaScript'
			},
			{
			  id: 2,
			  name: 'Basic'
			},
			{
			  id: 3,
			  name: 'PHP'
			},
			{
			  id: 4,
			  name: 'Java'
			}
		];
		setSearch(items); */
		//console.log(string, results)
	  }

	  const handleOnHover = (result) => {
		// the item hovered
		console.log(result)
	  }

	  const handleOnSelect = (item) => {
		// the item selected
		console.log(item)
	  }

	  const handleOnFocus = () => {
		console.log('Focused')
	  }

	  const formatResult = (item) => {
		return (
		  <>
			<Link href="" style={{ display: 'block', textAlign: 'left' }}>{item.name}</Link>
		  </>
		)
	  }	

	const breakPoints = [
	  { width: 1, itemsToShow: 1},
	  { width: 550, itemsToShow: 2, itemsToScroll: 1 },
	  { width: 768, itemsToShow: 3 },
	  { width: 1200, itemsToShow: 3 }
	];
  return (
	<>
	

		<header className="main-header header-two">
     
	 
      <div className="header-upper bg-white">
        <div className="container-fluid clearfix">
          <div className="header-inner rel d-flex align-items-center">
            <div className="logo-outer">
              <div className="logo">
                <Link legacyBehavior href="/">
                  <a>
                    <img
                      src="assets/images/logos/logo.png"
                      alt="Logo"
                      title="Logo"
                    />
                  </a>
                </Link>
              </div>
            </div>
            <div className="nav-outer mx-auto clearfix">
              {/* Main Menu */}
			  <div style={{ width: 500 }}>
				  <ReactSearchAutocomplete
					items={search}
					onSearch={handleOnSearch}
					onHover={handleOnHover}
					onSelect={handleOnSelect}
					onFocus={handleOnFocus}
					resultStringKeyName="title"
					autoFocus
					//fuseOptions={{ keys: ["title", "description"] }}
					formatResult={formatResult}
					styling={{
						 border: "1px solid #dfe1e5",
						 //borderRadius: "24px",
						 backgroundColor: "white",
						 boxShadow: "rgba(32, 33, 36, 0.28) 0px 1px 6px 0px",
						 hoverBackgroundColor: "#eee",
						 color: "#212121",
						 fontSize: "16px",
						 fontFamily: "Arial",
						 iconColor: "grey",
						 lineColor: "rgb(232, 234, 237)",
						 placeholderColor: "grey",
						 clearIconMargin: '3px 14px 0 0',
						 searchIconMargin: '0 0 0 16px'
					}}
				  />
				  </div>
              {/* Main Menu End*/}
            </div>
            {/* Nav Search */}
            
            {/* Menu Button */}
			{ isLogin === false ? 
				<div className="menu-btns">
				  <Link legacyBehavior href="">
					<a className="theme-btn1" onClick={() => signin()}>
					  Sign in
					</a>
				  </Link>
				  <Link legacyBehavior href="">
					<a className="theme-btn1"  onClick={() => signup()}>
					  Sign Up
					</a>
				  </Link>
				</div>
			:
				<div className="menu-btns">
				  <Link legacyBehavior href="">
					<a className="theme-btn1" onClick={() => logout()}>
					  Logout
					</a>
				  </Link>
				</div>
			}
				
            {/* Header Social 
            <div className="social-style-two">
              <a href="#">
                <i className="fab fa-facebook-f" />
              </a>
              <a href="#">
                <i className="fab fa-twitter" />
              </a>
              <a href="#">
                <i className="fab fa-instagram" />
              </a>
              <a href="#">
                <i className="fab fa-linkedin-in" />
              </a>
            </div>*/}
          </div>
        </div>
      </div>
      {/*End Header Upper*/}
    </header>
  
		<Layout>
		  <section
			className="hero-area-two bgs-cover"
			style={{ backgroundImage: "url(assets/images/hero/hero-two.jpg)" }}
		  >
			<div className="container">
			  {/*
			  <div className={styles.contWrapper}>
			  <Carousel breakPoints={breakPoints} showIndicators={false} infiniteLoop={false} showArrows={true}>
				  <Fancybox
					options={{
					  Carousel: {
						infinite: false,
					  },
					}}
				  >
					<a data-fancybox="gallery" href="https://lipsum.app/id/60/1600x1200">
					  <img
						alt=""
						src="https://lipsum.app/id/60/200x150"
						width="200"
						height="150"
					  />
					</a>
					<a data-fancybox="gallery" href="https://lipsum.app/id/61/1600x1200">
					  <img
						alt=""
						src="https://lipsum.app/id/61/200x150"
						width="200"
						height="150"
					  />
					</a>
					<a data-fancybox="gallery" href="https://lipsum.app/id/62/1600x1200">
					  <img
						alt=""
						src="https://lipsum.app/id/62/200x150"
						width="200"
						height="150"
					  />
					</a>
					<a data-fancybox="gallery" href="https://lipsum.app/id/61/1600x1200">
					  <img
						alt=""
						src="https://lipsum.app/id/61/200x150"
						width="200"
						height="150"
					  />
					</a>
					<a data-fancybox="gallery" href="https://lipsum.app/id/62/1600x1200">
					  <img
						alt=""
						src="https://lipsum.app/id/62/200x150"
						width="200"
						height="150"
					  />
					</a>
					<a data-fancybox="gallery" href="https://lipsum.app/id/61/1600x1200">
					  <img
						alt=""
						src="https://lipsum.app/id/61/200x150"
						width="200"
						height="150"
					  />
					</a>
					<a data-fancybox="gallery" href="https://lipsum.app/id/62/1600x1200">
					  <img
						alt=""
						src="https://lipsum.app/id/62/200x150"
						width="200"
						height="150"
					  />
					</a>
				  </Fancybox>
				  </Carousel>
				</div>	
	*/}
			 <div className={styles.contWrapper}>
			 <Fancybox
					options={{
					  Carousel: {
						infinite: false,
					  },
					}}
				  >
				<Carousel breakPoints={breakPoints} showIndicators={false} infiniteLoop={false} showArrows={true}>
				
				
				  {reelslist.map((item) => (
					<div
					  key={item.id}
					  className={styles.card}
					  style={{ backgroundImage: `url(${item.id})` }}
					>
						
					  <div className={styles.title}>
						
						<div>
							{(() => {
								switch (item.reel_type) {
								  case 1:
									return <><video width='120' height='80' controls>
									  <source src={item.link} type='video/mp4' />
									</video><br /><a data-fancybox="gallery" href={item.link}><h3>{item.name}</h3></a></>
								  case 2:
									return <><img src={item.thumb} alt="" height={100} width={120} />
										<br /><a data-fancybox="gallery" href={item.link}><h3>{item.name}</h3></a></>
								  case 3:
									return <><img src={item.link} alt="" height={100} width={120} />
											<br /><a data-fancybox="gallery" href={item.link}><h3>{item.name}</h3></a></>
								  
								  default:
									return null
								}
							  })()}
						</div>
					  </div>
					</div>
				  ))}
				  
				  
				</Carousel></Fancybox>
			  </div>

			  <div className="row justify-content-center">
			  <Carousel breakPoints={breakPoints} showIndicators={false} infiniteLoop={false} showArrows={true}>
				{categories.map((category, cati) => (
					
					  <>
						
							{(() => {
								switch (category.story_type) {
								  case 1:
									return <><a target="_blank" href={category.link}><img src={category.image_path} alt="" height={100} width={120} /></a></>
								  case 2:
									return <><a target="_blank" href={category.image_path}><video width='120' height='80' controls>
									  <source src={category.image_path} type='video/mp4' />
									</video></a></>
								  
								  default:
									return null
								}
							  })()}
							  
					  </>
				))}
				 </Carousel>
				
			  </div>
			  
			</div>
			<div className="hero-shapes-two">
			  <img src="assets/images/hero/hero-bg-lines.png" alt="BG Lines" />
			</div>
		  </section>
		  
		  
		  <section className="blog-area-two pb-100 rpb-70 rel z-1">
			<div className="container">
			  <div className="row align-items-end pb-35">
				
			  </div>
			  <div className="row justify-content-center">
			  
				{post.map((postobj, posti) => (  
					<div className="col-xl-4 col-md-4">
					  <div className="blog-item wow-x fadeInUp-x delay-0-2s-x">
						<div className="image">
						  <img src={postobj.image_path} err="assets/images/blog/blog1.jpg" alt="" height={300} />
						</div>
						<div className="content">
						 
						  <h4>
							<Link href="" key={postobj.id} onClick={() => handleView(event, postobj.id)}>
							  {postobj.title}
							</Link>
						  </h4>
						  <div className="author-more">
							
							<span className="author" onClick={() => handleIncrement(event, postobj.id)}>
								{ (isLogin === true && postobj.likestatus > 0) ?
									<i className="fas fa-heart" aria-hidden="true"></i>
								:
									<i className="far fa-heart" aria-hidden="true"></i>
								}
								({postobj.like})
							</span>
							
							<span className="author">
							  <i className="fa fa-share-alt" aria-hidden="true"></i>
								({postobj.share})
							</span>
							
							<span className="author">
							  <i className="fa fa-eye" aria-hidden="true"></i>
								({postobj.view})
							</span>
							
							<span className="author">
							  <i className="fa fa-comment" aria-hidden="true"></i>
								({postobj.comment})
							</span>
							
						  </div>
						</div>
					  </div>
					  {/*
					  <Modal setActive={handleView} fade={false} size="xl" key={postobj.id}>
							<Modal.Header>
								<Modal.Title>
									<button type="button" onClick='' data-dismiss="modal" className="close">×</button>
									<h4 className="modal-title md-header">Title</h4>
								</Modal.Title>
							</Modal.Header>
										
							<Modal.Body>
								<div className=''>
									({postobj.id})
								</div>
								
							</Modal.Body>
									
						</Modal>
					  */}
					</div>
					
					
					
				))}
				
			  </div>
			</div>
		  </section>
		  
		  
		 
		  
		  <Modal show={show} fade={false}>
				<Modal.Header>
					<Modal.Title>
						<button type="button" onClick={handleClose} data-dismiss="modal" className="close">×</button>
						<h4 className="modal-title md-header">{modaltitle}</h4>
					</Modal.Title>
				</Modal.Header>
							
				<Modal.Body>
					<div className={loginForm}>
						<form onSubmit={login}>
						
							{loginErr.map((loginErr, logErri) => (
								<>
									<span style={{'color':'red'}}>{loginErr}</span><br/>
								</>
							))}
						
							<div className="col-md-12">
								<div className="col-md-12">
									<label htmlFor="exampleInputEmail1">Email</label>
									<input type="text" placeholder="Email" value={lemail} onChange={(e) => setLemail(e.target.value)} className="form-control" />
								</div>
								
								<div className="col-md-12">
									<label htmlFor="exampleInputEmail1">Password</label>
									<input type="password" placeholder="password" value={lpassword} onChange={(e) => setLpassword(e.target.value)} className="form-control" />
								</div>
							</div>
							
							
							<div className="col-md-12"><br/>
								<input type="submit" disabled={loginDisabled}  className="btn btn-primary" value="Login" style={{'float':'right'}} />
								<Link href=""onClick={handleRegister}>Register</Link>
								
							</div>
						</form>
					</div>
					
					<div className={registerForm}>
						<form onSubmit={register}>
						
							{registerErr.map((regErr, regErri) => (
								<>
									<span style={{'color':'red'}}>{regErr}</span><br/>
								</>
							))}
						
							<div className="col-md-12">
								<div className="col-md-12">
									<label htmlFor="exampleInputEmail1">Name {registerForm}</label>
									<input type="text" placeholder="Name" value={rname} onChange={(e) => setRname(e.target.value)} className="form-control" />
								</div>
								<div className="col-md-12">
									<label htmlFor="exampleInputEmail1">Email</label>
									<input type="text" placeholder="Email" value={remail} onChange={(e) => setRemail(e.target.value)} className="form-control" />
								</div>
								
								<div className="col-md-12">
									<label htmlFor="exampleInputEmail1">Password</label>
									<input type="password" placeholder="Password"  value={rpassword} onChange={(e) => setRpassword(e.target.value)} className="form-control" />
								</div>
							</div>
							
							<div className="col-md-12"><br/>
								<input type="submit" disabled={registerDisabled}  className="btn btn-primary" value="Register" style={{'float':'right'}} />
								<Link href="" onClick={handleLogin}>Login</Link>
							
							</div>
						</form>
					</div>
					
				</Modal.Body>
							
			</Modal>
			
			

			<Modal show={isOpen} fade={false} size="xl">
				<Modal.Header>
					<Modal.Title>
						<button type="button" onClick={handleClose} data-dismiss="modal" className="close">×</button>
						<h4 className="modal-title md-header">Detail</h4>
					</Modal.Title>
				</Modal.Header>
							
				<Modal.Body>
					<div className=''>
					{Object.keys(postDetail).length === 0 ? (
						<span>No Record</span>
						 ) : (
						
							 <div className="row">
								<div className="col-md-8">
									<div style={{"height":"600px"}}>
										<div className="carousel-inner" role="listbox">
											<div className="item">
												<img src={postDetail.image_path} alt="" height={500} />
											</div>
										</div>
									</div>
								</div>
								<div className="col-md-4">
									<div className="modal-body inline">
										<div className="row">
											<div className="col-md-3">
													
											</div>
											<div className="col-md-9">
												{postDetail.title}
												<br/>
												<span className="glyphicon glyphicon-heart"></span> Icon
											</div>
										</div>
									   <hr/>
									   <div className="author-more">
											<span className="author"><i className="far fa-heart" aria-hidden="true"></i>
											{ (postCountStatus.like > postDetail.like) ?
												<span onClick={() => handleIncrement(event, postDetail.id)}>{postCountStatus.like}</span>
											:
												<span onClick={() => handleIncrement(event, postDetail.id)}>{postDetail.like}</span>
											}
											</span>
											<span className="author"><i className="fa fa-share-alt" aria-hidden="true"></i>
											{ (postCountStatus.share > postDetail.share) ?
												<span>{postCountStatus.share}</span>
											:
												<span>{postDetail.share}</span>
											}
											</span>
											
											<span className="author"><i className="fa fa-eye" aria-hidden="true"></i>
											{ (postCountStatus.view > postDetail.view) ?
												<span>{postCountStatus.view}</span>
											:
												<span>{postDetail.view}</span>
											}
											</span>
											
											<span className="author"><i className="fa fa-comment" aria-hidden="true"></i>
											
											{ (postCountStatus.comment > postDetail.comment) ?
												<span>{postCountStatus.comment}</span>
											:
												<span>{postDetail.comment}</span>
											}
											</span>
										</div>
									   { (postDetail.isAuth != null) ?
											
											<form onSubmit={commentsave(postDetail.id)}>
												<input placeholder="Comment"  value={comment} onChange={(e) => setComment(e.target.value)}  type="text" style={{"height":"100px"}} className="form-control" />
												<input type="submit" disabled={commentDisabled}  className="btn btn-primary" value="Save" style={{'float':'right'}} />
												<span className="text-mute">Please write your openion.</span>
												
											</form>
										:
											<span></span>
										
										}

										{ 
										
											postDetailComment.map((postDetailCommentObj, i) => {
												return <><p>{postDetailCommentObj.comment}</p></>
											})
											
										}
										
										{(() => {
											if(loadComment > 0) {
												return <span type="submit"className="btn btn-primary" onClick={loadcommentdata(postDetail.id, loadComment)} style={{'float':'left'}}>Load More</span>
											}
										})()}
											
										<br/>

									</div>
								</div>
							</div>
						)}
					</div>
					
					</Modal.Body>
						
			</Modal>

		</Layout>
	</>
  );
};

export async function getServerSideProps({ req, res, params }) {

	let url = SITE_URL+`/post`;
	console.log(params.slug[0]);
	
	if(params != undefined) {
		if(params.slug[0] != undefined) {
			url = SITE_URL+`/post/${params.slug[0]}`;
		}
	}
	console.log(url);
	let headerAccess = localData.headerAccess();
	var config1 = {
		headers: headerAccess
	};
	if (typeof window !== 'undefined') {
		const token = localStorage.getItem("token");
		var config = {
			headers : "Authorization : Bearer "+token
		};
	}
	
	//const response = await fetch(url)
	//const data = await response.json();
	const response = await axios.get(url, config);
	const data = response.data;
	
	return {
		props: {
			data
		},
	};
}

export default Landing;
