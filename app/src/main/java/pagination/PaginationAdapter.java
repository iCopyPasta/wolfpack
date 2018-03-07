package pagination;

import android.support.v7.widget.RecyclerView;
import android.view.ViewGroup;

/**
 * Created by peo5032 on 3/7/18.
 */

public class PaginationAdapter extends RecyclerView.Adapter<RecyclerView.ViewHolder> {
    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        //TODO: implement
        return null;
    }

    @Override
    public void onBindViewHolder(RecyclerView.ViewHolder holder, int position) {
        //TODO: implement

    }

    @Override
    public int getItemCount() {
        //TODO: Implement
        return 0;
    }

    @Override
    public int getItemViewType(int position){
        //TODO: Implement
        return 0;
    }

    public void add() {
        //movies.add(mc);
        //notifyItemInserted();
    }

    public void addAll() {
        /*for () {
            add();
        }*/
    }

    public void remove() {
        /*int position = movies.indexOf(city);
        if (position > -1) {
            movies.remove(position);
            notifyItemRemoved(position);
        }*/
    }

    public void clear() {
        /*isLoadingAdded = false;
        while (getItemCount() > 0) {
            remove(getItem(0));
        }*/
    }

    public boolean isEmpty() {
        return getItemCount() == 0;
    }

    public void addLoadingFooter() {
        /*isLoadingAdded = true;
        add(new Movie());*/
    }

    public void removeLoadingFooter() {
        /*isLoadingAdded = false;

        int position = movies.size() - 1;
        Movie item = getItem(position);

        if (item != null) {
            movies.remove(position);
            notifyItemRemoved(position);
        }*/
    }

    /*public Movie getItem(int position) {
        return movies.get(position);
    }*/


}
