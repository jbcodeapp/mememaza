import { HOME_URL, SITE_URL, localData } from '../def';
import axios from 'axios';

import Landing from "./landing";

const Index = (props) => {
  return (
	<>
		<Landing props={props} />
	</>
  );
};

export async function getServerSideProps({ req, res, params }) {

	let url = SITE_URL+`/post`;
	if(params != undefined) {
		if(params.slug[0] != undefined) {
			url = SITE_URL+`/post/${params.slug[0]}`;
		}
	}

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

	const response = await axios.get(url, config);
	const data = response.data;

	return {
		props: {
			data
		},
	};
}

export default Index;
