import {Layout} from "antd";
import {useEffect, useState} from "react";
import axios from "axios";
import BooksListFormat from "./BooksListFormat";
import "antd/dist/antd.css";

const {Header, Content} = Layout;
const BOOKS_LIST_URL = 'http://localhost:8000/public/api/v1/all';

const noBooksMessage = () => <div style={{padding: "40px 0", textAlign: "center", fontSize: "28px"}}>'No books available :('</div>;

function App() {
    const [data, setData] = useState([]);
    useEffect(() => {
        const getBooksList = async () => {
            try {
                const response = await axios.get(BOOKS_LIST_URL);
                console.log(response.data);
                setData(response.data);
            } catch (e) {
                console.log(e);
                setData(data);
            }
        };
        getBooksList();
    }, []);

    return (
        <Layout>
            <Header style={{textAlign: "center", fontSize: "24px", color: "white"}}>Welcome to library</Header>
            <Content>{data.length !== 0 ? <BooksListFormat data={data}/> : noBooksMessage()}</Content>
        </Layout>
    );
}

export default App;
