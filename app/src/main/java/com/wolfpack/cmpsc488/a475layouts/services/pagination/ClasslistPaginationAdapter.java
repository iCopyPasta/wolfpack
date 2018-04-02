package com.wolfpack.cmpsc488.a475layouts.services.pagination;

import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ProgressBar;
import android.widget.TextView;

import com.wolfpack.cmpsc488.a475layouts.R;

import java.util.ArrayList;

import com.wolfpack.cmpsc488.a475layouts.services.pagination.models.ClassResult;
//import com.wolfpack.cmpsc488.a475layouts.services.student_class_management.EnrollClassDialog;


public class ClasslistPaginationAdapter extends RecyclerView.Adapter<RecyclerView.ViewHolder> {

    public interface OnClassClickedListener{
        void onClassClicked(String classId, String classTitle, String classDesc, String classOffering, String classLocation, String teacherName);
    }

    private static final int VIEW_TYPE_ITEM = 0, VIEW_TYPE_LOADING = 1;
    private static final String TAG = "ClasslistAdapter";
    private ILoadmore loadmore;
    private boolean isLoading;
    private ArrayList<ClassResult> items = new ArrayList<>();
    private final int visibleThreshold = 8;
    private int serverTotal = Integer.MAX_VALUE;
    private int lastVisibleItem, totalItemCount;
    private int pageNumber = 1;

    private OnClassClickedListener listener;

    public ClasslistPaginationAdapter(
            RecyclerView recyclerView,
            ArrayList<ClassResult> items){

        this.items = items;

        final LinearLayoutManager linearLayoutManager =
                (LinearLayoutManager) recyclerView.getLayoutManager();

        recyclerView.addOnScrollListener(new RecyclerView.OnScrollListener() {

            // THE LIFESAVER
            //https://stackoverflow.com/questions/36127734/detect-when-recyclerview-reaches-the-bottom-most-position-while-scrolling

            @Override
            public void onScrollStateChanged(RecyclerView recyclerView, int newState) {
                Log.i(TAG, "onScrollStateChanged");
                super.onScrollStateChanged(recyclerView, newState);

                if (!recyclerView.canScrollVertically(1)) {
                    //Toast.makeText(recyclerView.getContext(),"Last",Toast.LENGTH_SHORT).show();

                    totalItemCount = linearLayoutManager.getItemCount();
                    Log.i(TAG, "onScrollState: totalItemCount = " + totalItemCount);
                    lastVisibleItem = linearLayoutManager.findLastVisibleItemPosition();
                    Log.i(TAG, "onScrolledState: lastVisibleItem = " + lastVisibleItem);

                    if(!isLoading && pageNumber < serverTotal){
                        if(loadmore != null){
                            Log.i(TAG, "onScrolled: " + serverTotal);
                            Log.i(TAG, "calling onLoadMore");
                            pageNumber++;
                            loadmore.onLoadMore();
                            isLoading = true;
                        }
                    }

                }
            }
        });
    }


    public void setOnClassClickedListener(OnClassClickedListener listener){
                this.listener = listener;
    }

    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        if(viewType == VIEW_TYPE_ITEM){
            View view = LayoutInflater.from(parent.getContext())
                    .inflate(R.layout.recyclerview_student_page_classlist_item,
                            parent,
                            false);

            return new ItemViewHolder(view);
        }
        else{
            if(viewType == VIEW_TYPE_LOADING){
                View view = LayoutInflater.from(parent.getContext())
                        .inflate(R.layout.item_loading, parent, false);

                return new LoadingViewHolder(view);
            }
        }
        return null;
    }

    @Override
    public void onBindViewHolder(RecyclerView.ViewHolder holder, int position) {

        if(holder instanceof ItemViewHolder){
            ClassResult item = items.get(position);
            ItemViewHolder viewHolder = (ItemViewHolder) holder;

            viewHolder.class_id = item.getClassId();
            viewHolder.offering = item.getOffering();
            viewHolder.location = item.getLocation();
            viewHolder.title = item.getTitle();
            viewHolder.description = item.getDescription();
            viewHolder.teacher_name = item.getFirstName() + " " + item.getLastName();

            viewHolder.mClassNameDisplay.setText(viewHolder.title);
            viewHolder.mTeacherNameDisplay.setText(viewHolder.teacher_name);

        } else if(holder instanceof LoadingViewHolder){
            LoadingViewHolder loadingViewHolder = (LoadingViewHolder) holder;
            loadingViewHolder.progressBar.setIndeterminate(true);
        }

    }

    @Override
    public int getItemCount() {
        return items.size();
    }

    public int getRowsPerPage(){
        return visibleThreshold;
    }

    public void setServerTotal(int totalItemCount){
        Log.i(TAG, "setServerTotal: " + totalItemCount);
        this.serverTotal = totalItemCount;
    }

    public void clearData(){
        serverTotal = Integer.MAX_VALUE;
        pageNumber = 1;
        items.clear();
        notifyDataSetChanged();
    }

    @Override
    public int getItemViewType(int position){
        return items.get(position) == null ? VIEW_TYPE_LOADING: VIEW_TYPE_ITEM;
    }

    //Custom functions used to interact with a given fragment
    public void setLoadmore(ILoadmore loadmore){
        this.loadmore = loadmore;
    }

    public void setLoaded(){
        this.isLoading = false;
    }

    public synchronized int getLastPageNumber(){
        Log.i(TAG, "getLastPageNumber: " + pageNumber);
        return pageNumber;
    }

    //View Holders

    class LoadingViewHolder extends RecyclerView.ViewHolder{

        ProgressBar progressBar;

        LoadingViewHolder(View itemView){
            super(itemView);
            progressBar = itemView.findViewById(R.id.paginationProgressBar);
        }
    }

    class ItemViewHolder extends RecyclerView.ViewHolder
            implements View.OnClickListener {

        Boolean isRunning = false;

        String class_id;
        String title;
        String description;
        String location;
        String offering;
        String teacher_name;

        TextView mTeacherNameDisplay;
        TextView mClassNameDisplay;

        ItemViewHolder(View itemView){
            super(itemView);

            mTeacherNameDisplay = (TextView) itemView.findViewById(R.id.teacherNameDisplay);
            mClassNameDisplay = (TextView) itemView.findViewById(R.id.classNameDisplay);


            /*title= (TextView) itemView.findViewById(R.id.txtTitle);
            description= (TextView) itemView.findViewById(R.id.txtDescription);
            location = (TextView) itemView.findViewById(R.id.txtLocation);
            offering = (TextView) itemView.findViewById(R.id.txtOffering);
*/
            itemView.setOnClickListener(this);

        }

        @Override
        public void onClick(View view){
            listener.onClassClicked(class_id, title, description, offering, location, teacher_name);
        }


    }


}

