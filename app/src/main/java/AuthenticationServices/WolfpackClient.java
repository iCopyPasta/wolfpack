package AuthenticationServices;


import com.google.gson.Gson;
import com.google.gson.GsonBuilder;

import java.util.List;

import retrofit2.Call;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;
import retrofit2.http.Body;
import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.GET;
import retrofit2.http.POST;

public interface WolfpackClient{

    String BASE_URL = "http://wolfpack.cs.hbg.psu.edu/";
    //String BASE_URL = "http://192.168.1.57";
    //String FEED = "/Sign_up.php";
    String FEED = "pablo_test_android/Sign_up.php";
    String LOGIN = "pablo_test_android/Sign_in_student.php";

    Gson gson = new GsonBuilder()
            .setLenient()
            .create();

    Retrofit retrofit = new Retrofit.Builder()
            .baseUrl(BASE_URL)
            .addConverterFactory(GsonConverterFactory.create(gson))
            .build();

    @FormUrlEncoded
    @POST(LOGIN)
    Call<LoginDetails> attemptLogin(
            @Field("inputEmail") String email,
            @Field("inputPassword") String password
    );

    @FormUrlEncoded
    @POST(FEED)
    Call<LoginDetails> attemptRegister(
            @Field("inputFirstName") String first_name,
            @Field("inputLastName") String last_name,
            @Field("inputEmail") String email,
            @Field("inputPassword") String password
    );

    //TODO: DEFINE CALL HERE BASED FOR LOGIN

}