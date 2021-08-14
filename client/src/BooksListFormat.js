import {PageHeader,Descriptions} from 'antd';
import {Content} from "antd/es/layout/layout";


const formatDate = (date) => (new Date(date)).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
})

const BooksListFormat = (props) => {
    return <div>
        {props.data.map(value =>
            <div style={{padding: "24px", backgroundColor: "#f5f5f5"}}>
                <PageHeader ghost={false} title={value.author.name} subTitle="Author">
                    <Descriptions column={2}>
                        <Descriptions.Item label="Country">{value.author.country}</Descriptions.Item>
                        <Descriptions.Item label="Birthday">{formatDate(value.author.birthday.date)}</Descriptions.Item>
                    </Descriptions>
                    <Content>{value.author.description}</Content>
                </PageHeader>
                {value.books.map(book =>
                    <PageHeader ghost={false} title={book.title} subTitle={value.author.name + "'s book"}>
                        <Descriptions column={2}>
                            <Descriptions.Item label="Genre">{book.genre}</Descriptions.Item>
                            <Descriptions.Item label="Created at">{formatDate(book.writingDate.date)}</Descriptions.Item>
                        </Descriptions>
                        <Content>{book.description}</Content>
                    </PageHeader>
                )}
            </div>
        )}
    </div>
};

export default BooksListFormat;