package pagination;

import android.app.Activity;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import com.wolfpack.cmpsc488.a475layouts.R;
import com.wolfpack.cmpsc488.a475layouts.experiences.student.StudentPage;

import java.util.ArrayList;
import pagination.models.SearchResultSection;

/**
 * Created by peo5032 on 3/7/18.
 */

public class PaginationAdapter extends RecyclerView.Adapter<RecyclerView.ViewHolder> {

    private static final int VIEW_TYPE_ITEM = 0, VIEW_TYPE_LOADING = 1;
    private static final String TAG = "PaginationAdapter";
    private ILoadmore loadmore;
    private boolean isLoading;
    private Activity activity;
    private ArrayList<SearchResultSection> items;
    private int visibleThreshold = 5;
    private int lastVisibleItem, totalItemCount;

    public PaginationAdapter(
            RecyclerView recyclerView,
            Activity activity,
            ArrayList<SearchResultSection> items){

        this.activity = activity;
        this.items = items;

        final LinearLayoutManager linearLayoutManager =
                (LinearLayoutManager) recyclerView.getLayoutManager();

        recyclerView.addOnScrollListener(new RecyclerView.OnScrollListener() {
            @Override
            public void onScrolled(RecyclerView recyclerView, int dx, int dy) {
                Log.i(TAG, "onScrolled called");
                super.onScrolled(recyclerView, dx, dy);
                totalItemCount = linearLayoutManager.getItemCount();
                lastVisibleItem = linearLayoutManager.findLastVisibleItemPosition();

                if(!isLoading && totalItemCount <= (lastVisibleItem + visibleThreshold)){
                    if(loadmore != null){
                        Log.i(TAG, "calling onLoadMore");
                        loadmore.onLoadMore();
                        isLoading = true;
                    }
                }

            }

            // THE LIFESAVER
            //https://stackoverflow.com/questions/36127734/detect-when-recyclerview-reaches-the-bottom-most-position-while-scrolling

            @Override
            public void onScrollStateChanged(RecyclerView recyclerView, int newState) {
                Log.i(TAG, "onScrollStateChanged");
                super.onScrollStateChanged(recyclerView, newState);

                if (!recyclerView.canScrollVertically(1)) {
                    Toast.makeText(recyclerView.getContext(),"Last",Toast.LENGTH_SHORT).show();

                }
            }
        });
    }

    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
         if(viewType == VIEW_TYPE_ITEM){
             View view = LayoutInflater.from(activity)
                     .inflate(R.layout.item_layout, parent, false);

             return new ItemViewHolder(view);
         }
         else{
             if(viewType == VIEW_TYPE_LOADING){
                 View view = LayoutInflater.from(activity)
                         .inflate(R.layout.item_loading, parent, false);

                 return new LoadingViewHolder(view);
             }
         }
        return null;
    }

    @Override
    public void onBindViewHolder(RecyclerView.ViewHolder holder, int position) {
        //TODO: implement

        if(holder instanceof ItemViewHolder){
            SearchResultSection item = items.get(position);
            ItemViewHolder viewHolder = (ItemViewHolder) holder;
            viewHolder.className.setText(item.getClassTitle());
            viewHolder.offering.setText(item.getOffering());
            viewHolder.location.setText(item.getLocation());
            viewHolder.section.setText(item.getClassSectionNumber());

        } else if(holder instanceof LoadingViewHolder){
            LoadingViewHolder loadingViewHolder = (LoadingViewHolder) holder;
            loadingViewHolder.progressBar.setIndeterminate(true);
        }

    }

    @Override
    public int getItemCount() {
        return items.size();
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

    public int getLastPageNumber(){
        int retVal = (int) getItemCount() / visibleThreshold;
        if(retVal <= 0)
            return 1;
        else
            return retVal;
    }

    //View Holders

    class LoadingViewHolder extends RecyclerView.ViewHolder{

        public ProgressBar progressBar;

        public LoadingViewHolder(View itemView){
            super(itemView);
            progressBar = (ProgressBar) itemView.findViewById(R.id.paginationProgressBar);

        }
    }

    class ItemViewHolder extends RecyclerView.ViewHolder{
        public TextView className, section, location, offering;

        public ItemViewHolder(View itemView){
            super(itemView);
            className = (TextView) itemView.findViewById(R.id.txtClassName);
            section = (TextView) itemView.findViewById(R.id.txtSectionNumber);
            location = (TextView) itemView.findViewById(R.id.txtLocation);
            offering = (TextView) itemView.findViewById(R.id.txtOffering);


        }
    }
}
