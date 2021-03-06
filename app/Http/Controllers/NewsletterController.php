<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateNewsletterRequest;
use App\Http\Requests\UpdateNewsletterRequest;
use App\Models\Newsletter;
use App\Repositories\NewsletterRepository;
use App\Http\Controllers\AppBaseController;
use App\User;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Mail;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class NewsletterController extends AppBaseController
{
    /** @var  NewsletterRepository */
    private $newsletterRepository;

    public function __construct(NewsletterRepository $newsletterRepo)
    {
        $this->newsletterRepository = $newsletterRepo;
    }

    /**
     * Display a listing of the Newsletter.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->newsletterRepository->pushCriteria(new RequestCriteria($request));
        $newsletters = $this->newsletterRepository->all();

        return view('newsletters.index')
            ->with('newsletters', $newsletters);
    }

    /**
     * Show the form for creating a new Newsletter.
     *
     * @return Response
     */
    public function create()
    {
        return view('newsletters.create');
    }

    /**
     * Store a newly created Newsletter in storage.
     *
     * @param CreateNewsletterRequest $request
     *
     * @return Response
     */
    public function store(CreateNewsletterRequest $request)
    {
        $input = $request->all();

        $newsletter = $this->newsletterRepository->create($input);

        $users = User::select('email')->where('flag', 1)->pluck('email');

            $data = array(
                'email' => $users,
                'subject' => $request->subject,
                'content' => $newsletter->content,
                );
            Mail::send('emails.newsletter', $data, function($message) use ($data){
                $message->from('info@jeddah.org');
                $message->to( $data['email'] );
                $message->subject('Newsletter');
            });
    
        
        // $users = User::select('email')->where('flag', 1)->get()->toArray();

        // if(isset($users)){
        //     $emails = array_pluck($users, 'email');

        //     Mail::send('emails.newsletter', [
        //         'content' => $newsletter->content

        //     ], function ($message) use ($request, $emails) {

        //         $message->from('info@jeddah.org', 'Jeddah Attraction');

        //         $message->subject("Newsletter");

        //         $message->to($emails);
        //     });
        // }

        Flash::success('Newsletter saved successfully.');

        return redirect(route('newsletters.index'));
    }

    /**
     * Display the specified Newsletter.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $newsletter = $this->newsletterRepository->findWithoutFail($id);

        if (empty($newsletter)) {
            Flash::error('Newsletter not found');

            return redirect(route('newsletters.index'));
        }

        return view('newsletters.show')->with('newsletter', $newsletter);
    }

    /**
     * Show the form for editing the specified Newsletter.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $newsletter = $this->newsletterRepository->findWithoutFail($id);

        if (empty($newsletter)) {
            Flash::error('Newsletter not found');

            return redirect(route('newsletters.index'));
        }

        return view('newsletters.edit')->with('newsletter', $newsletter);
    }

    /**
     * Update the specified Newsletter in storage.
     *
     * @param  int              $id
     * @param UpdateNewsletterRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateNewsletterRequest $request)
    {
        $newsletter = $this->newsletterRepository->findWithoutFail($id);

        if (empty($newsletter)) {
            Flash::error('Newsletter not found');

            return redirect(route('newsletters.index'));
        }

        $newsletter = $this->newsletterRepository->update($request->all(), $id);

        $users = User::select('email')->where('flag', 1)->pluck('email');

            $data = array(
                'email' => $users,
                'subject' => $request->subject,
                'content' => $newsletter->content,
                );
            Mail::send('emails.newsletter', $data, function($message) use ($data){
                $message->from('info@jeddah.org');
                $message->to( $data['email'] );
                $message->subject('Newsletter');
            });
    
        // $users = User::select('email')->where('flag', 1)->get()->toArray();

        // if(isset($users)){
        //     $emails = array_pluck($users, 'email');

        //     Mail::send('emails.newsletter', [
        //         'content' => $newsletter->content

        //     ], function ($message) use ($request, $emails) {

        //         $message->from('info@jeddah.org', 'Jeddah Attraction');

        //         $message->subject("Newsletter");

        //         $message->to($emails);
        //     });
        // }

        Flash::success('Newsletter updated successfully.');

        return redirect(route('newsletters.index'));
    }

    /**
     * Remove the specified Newsletter from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $newsletter = $this->newsletterRepository->findWithoutFail($id);

        if (empty($newsletter)) {
            Flash::error('Newsletter not found');

            return redirect(route('newsletters.index'));
        }

        $this->newsletterRepository->delete($id);

        Flash::success('Newsletter deleted successfully.');

        return redirect(route('newsletters.index'));
    }
}
