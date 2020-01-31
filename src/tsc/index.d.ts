declare var wp: IWP;
declare var TutoMVC: TutoMVC;

declare interface IWP
{
	media( options: any );
}

declare interface TutoMVC
{
	parseURL: string;
	parseNonce: string;
	parseAction: string;
}

//declare var jQuery:any;

declare interface WPMediaAttachmentModel
{
	src?: string;
	alt: string;
	author: string;
	authorName: string;
	caption: string;
	compat: Object;
	date: Date;
	dateFormatted: string;
	description: string;
	editLink: string;
	filename: string;
	filesizeHumanReadable: string;
	filesizeInBytes: number;
	height: string;
	icon: string;
	id: string;
	link: string;
	menuOrder: number;
	meta: any;
	mime: string;
	modified: Date;
	name: string;
	nonces: Object;
	orientation: string;
	sizes: any;
	status: string;
	subtype: string;
	title: string;
	type: string;
	uploadedTo: string;
	uploadedToLink: string;
	uploadedToTitle: string;
	url: string;
	width: string;
}