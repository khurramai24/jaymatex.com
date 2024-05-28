<?php

namespace App\Http\Controllers;

use App\Article;
use App\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use ashtaev\Toc;

class webController extends Controller
{

    public function redirectIndex(){
        return redirect('en');
    }

    public function index($lang = "en"){
        return view($lang . '/home')->with([
            'lang' => $lang
        ]);
    }

    public function indexx(){
        $relateds = Article::where('cat', 'case-studies')->where('isactive', 1)->orderBy('id', 'DESC')->take(3)->get();
        return view('home')->with([
            'relateds' => $relateds
        ]);
    }

    public function page($lang, $slug){
        return view($lang . '/' . $slug)->with([
            'lang' => $lang
        ]);
    }
    
    public function sitemap(){
        $articles = Article::where('isactive', 1)->get();
        $content = view('sitemap', ['articles' => $articles]);
        return response($content)->header('Content-Type', 'text/xml;charset=utf-8')->header('Cache-Control', 'no-cache, must-revalidate');
    }

    public function articles($lang){
        $articles = Article::where('cat', $lang)->where('isactive', 1)->orderBy('id', 'DESC')->get();
        return view($lang . '/' . 'articles')->with([
            'articles' => $articles,
            'lang' => $lang
        ]);
    }

    public function article($lang, $slug){
        $article = Article::where('slug', $slug)->where('isactive', 1)->firstOrFail();
        $relateds = Article::where('id', '!=', $article->id)->where('isactive', 1)->where('cat', $article->cat)->orderBy('id', 'DESC')->take(6)->get();

        return view($lang . '/' . 'article')->with([
            'article' => $article,
            'title' => $article->titre,
            'contenu' => $article->contenu,
            'relateds' => $relateds,
            'lang' => $lang
        ]);
    }

    public function writeforus($lang){
        App::setLocale($lang);
        return view('writeforus')->with([
            'title' => "sqdqs"
        ]);
    }

    //BEGIN MANAGER
    public function getManagerLogin(){
        return view('manager.login');
    }

    public function postManagerLogin(Request $request){
        if($request->username === "admin" && $request->password === "jaymatex@2023"){
            Session::put('manager', 1);
        }
        return redirect('/manager');
    }

    public function logOutManager(){
        Session::forget('manager');
        return redirect('/manager');
    }

    public function getProduits(){
        $produits = Article::where('isactive', 1)->get();
        return view('manager.produits')->with([
            "produits" => $produits
        ]);
    }

    public function getContacts(){
        $contacts = Contact::get();
        return view('manager.contacts')->with([
            "produits" => $contacts
        ]);
    }
    
    public function delContacts($id){
        $contact = Contact::where('id',$id)->first();
        $contact->delete();
        return redirect('/manager/contacts');
    }

    public function postForm(Request $request){
        Contact::create([
            "name" => $request->name,
            "email" => $request->email,
            "country" => $request->country,
            "phone" => $request->phone,
            "message" => $request->message
        ]);
        return redirect("/p/" . $request->lang .  "/thankyou");
    }

    public function getAddProduit(){
        return view('manager.addproduit');
    }

    public function postAddProduit(Request $request){
        $photo = null;
        if($request->photo){
            $imgname = Str::random(10);
            $img = Image::make($request->photo);
            $img->save('images/' . $imgname . '.jpg');
            $photo = "/images/" . $imgname . ".jpg";
        }
        Article::create([
            "cat" => $request->cat,
            "slug" => $request->slug,
            "titre" => $request->titre,
            "isactive" => $request->isactive,
            "photo" => $photo,
            "contenu" => $request->contenu
        ]);
        return redirect('manager');
    }

    public function getProduit($id){
        $produit = Article::where('id', $id)->first();
        return view('manager.produit')->with([
            'produit' => $produit
        ]);
    }

    public function saveProduit(Request $request){
        $produit = Article::where('id', $request->id)->firstOrFail();
        $produit->cat = $request->cat;
        $produit->slug = $request->slug;
        $produit->titre = $request->titre;
        $produit->isactive = $request->isactive;
        $produit->contenu = $request->contenu;
        if($request->photo){
            $imgname = Str::random(10);
            $img = Image::make($request->photo);
            $img->save('images/' . $imgname . '.jpg');
            $produit->photo = "/images/" . $imgname . ".jpg";
        }
        $produit->save();
        return back();
    }
}
