import React from 'react';

//Aditional Components
import LoaderSpinner from "./../../Component/LoaderSpinner";

export default class Contact extends React.Component
{
    constructor()
    {
        super();

        this.state = {
            title: 'Contact Us',
            is_spiner_loader: false,
            name: '',
            email: '',
            phone: '',
            subject: '',
            message: '',
            alert_message: '',
            errors: ''
        };
    }

    handleSubmitForm ()
    {
        let formPost = {
            name: this.state.name,
            email: this.state.email,
            phone: this.state.phone,
            subject: this.state.subject,
            message: this.state.message,
        }

        try
        {
            this.setState({ is_spiner_loader: true });

            fetch(process.env.REACT_APP_APP_API_URL +  "/contact-form/save", {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(formPost),
            })
            .then((res) => res.json())
            .then((res) => {

                this.setState({ is_spiner_loader: false });

                console.log('res', res);

                if (res.status == 200)
                {
                    this.setState({
                        name: '',
                        email: '',
                        phone: '',
                        subject: '',
                        message: '',
                        alert_message: {
                            status: 'success',
                            message: res.message
                        },
                        errors: ''
                    });
                }
                else
                {
                    this.setState({
                        errors: res.error
                    });
                }
            })
        }
        catch (err) {
            console.log(err);
            console.log('err', err);

            this.setState({
                alert_message: {
                    status: 'danger',
                    message: err
                },
            })
        }

      /*  this.setState({
            is_spiner_loader: true
        });*/
    }

    render()
    {
        let contact_support = this.props.site_settings.site_setting? this.props.site_settings.site_setting.contact_support : null;

        const { is_spiner_loader } = this.state;

        if (is_spiner_loader) return  <LoaderSpinner />;

        return (
            <>
                <div className="container" style={{ marginTop:'70px' }}>
                    <h3 className="text-center">{ this.state.title }</h3>
                    <p className="text-center"><em>We love our fans!</em></p>

                    <div className="row">
                        <div className="col-md-4">
                            <p>Fan? Drop a note.</p>
                            <p><span className="glyphicon glyphicon-map-marker"></span> { contact_support ? contact_support.address : '' }</p>
                            <p><span className="glyphicon glyphicon-phone"></span> Phone: +00 1515151515</p>
                            <p><span className="glyphicon glyphicon-envelope"></span> Email: { contact_support ? contact_support.contact_email : '' }</p>
                        </div>
                        { this.state.alert_message.message ? <div className="alert alert-{ this.state.alert_message.status }" role="alert">{this.state.alert_message.message}</div> : null}
                        <div className="col-md-8">
                            <div className="row">
                                <div className="col-sm-12 form-group">
                                    <input value={ this.state.name } className="form-control" name="name" onChange={ (e) => this.setState({ name: e.target.value }) } placeholder="Name" type="text" required="" />
                                    { this.state.errors.name ? <p className="text-danger">{this.state.errors.name}</p> : ''}
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-sm-6 form-group">
                                    <input  value={ this.state.email } className="form-control" name="email" onChange={ (e) => this.setState({ email: e.target.value }) } placeholder="Email" type="text" required=""/>
                                    { this.state.errors.email ? <p className="text-danger">{this.state.errors.email}</p> : ''}
                                </div>
                                <div className="col-sm-6 form-group">
                                    <input  value={ this.state.phone } className="form-control" name="phone" onChange={ (e) => this.setState({ phone: e.target.value }) } placeholder="Phone" type="text" required=""/>
                                    { this.state.errors.phone ? <p className="text-danger">{this.state.errors.phone}</p> : ''}
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-sm-12 form-group">
                                    <input  value={ this.state.subject } className="form-control" name="subject" onChange={ (e) => this.setState({ subject: e.target.value }) } placeholder="Subject" type="text" required=""/>
                                    { this.state.errors.subject ? <p className="text-danger">{this.state.errors.subject}</p> : ''}
                                </div>
                            </div>
                            <textarea className="form-control" onChange={ (e) => this.setState({ message: e.target.value }) } name="comments" placeholder="Comment" rows="5">{ this.state.message }</textarea>
                            { this.state.errors.message ? <p className="text-danger">{this.state.errors.message}</p> : ''}
                            <br/>
                            <div className="row">
                                <div className="col-md-12 form-group">
                                    <button className="btn pull-right" onClick={ () => this.handleSubmitForm() } type="button">Send</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </>
        );
    }
}
